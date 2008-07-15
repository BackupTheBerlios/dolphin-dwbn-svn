function doShowHideSecondProfile( sShow, eForm ) {
	if( sShow == 'yes' )
		$( '.form_second_col', eForm ).css( 'display', '' );
	else
		$( '.form_second_col', eForm ).css( 'display', 'none' );
}

function validateEditForm( eForm ) {
	if( !eForm )
		return false;
	
	hideEditFormErrors( eForm );
	
	$(eForm).ajaxSubmit(function(sResponce) {
		//alert( sResponce );
		try {
			var aErrors = eval(sResponce);
		} catch(e) {
			return false;
		}
		
		doShowEditErrors( aErrors, eForm );
	} );
	
	return false;
}

function hideEditFormErrors( eForm ) {
	$( 'img.form_warn_icon', eForm ).css( 'display', 'none' );
	$( '.input_erroneus', eForm ).removeClass( 'input_erroneus' );
}

function doShowEditErrors( aErrors, eForm ) {
	if( !aErrors || !eForm )
		return false;
	
	var bHaveErrors = false;
	
	for( var iInd = 0; iInd < aErrors.length; iInd ++ ) {
		var aErrorsInd = aErrors[iInd];
		for( var sField in aErrorsInd ) {
			var sError = aErrorsInd[ sField ];
			bHaveErrors = true;
			
			doShowError( eForm, sField, iInd, sError );
		}
	}
	
	if( !bHaveErrors )
		eForm.submit();
}

function doShowError( eForm, sField, iInd, sError ) {
	var $Field = $( "[name='" + sField + "']", eForm ); // single (system) field
	if( !$Field.length ) // couple field
		$Field = $( "[name='" + sField + '[' + iInd + ']' + "']", eForm );
	if( !$Field.length ) // couple multi-select
		$Field = $( "[name='" + sField + '[' + iInd + '][]' + "']", eForm );
	if( !$Field.length ) // couple range (two fields)
		$Field = $( "[name='" + sField + '[' + iInd + '][0]' + "'],[name='" + sField + '[' + iInd + '][1]' + "']", eForm );
	
	//alert( sField + ' ' + $Field.length );
	
	$Field.addClass( 'input_erroneus' );
	
	$Icon = $Field.siblings( 'img.form_warn_icon' );
	$Icon.css( 'display', '' );
	$Icon.mouseover( function(){ showFloatDesc(sError) } );
}
