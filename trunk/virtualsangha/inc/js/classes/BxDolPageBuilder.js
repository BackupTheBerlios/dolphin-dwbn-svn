function BxDolPageBuilder( options ) {
	this.options = options;
	this.loadAreas();
	
	
}

BxDolPageBuilder.prototype.resetPage = function() {
	if( !confirm( 'Are you sure want to reset this page?\nThe page contents will be reset to factory state!' ) )
		return false;
	
	$.post(
		this.options.parser,
		{
			action: 'resetPage',
			Page: this.options.page
		},
		function() {
			location.reload();
		}
	);
}

BxDolPageBuilder.prototype.loadAreas = function() {
	var _builder = this;
	
	this.activeArea   = $( '#activeBlocksArea'   ).get(0);
	this.inactiveArea = $( '#inactiveBlocksArea' ).get(0);
	this.samplesArea  = $( '#samplesBlocksArea'  ).get(0);
	this.eAllAreas    = $(this.activeArea).add(this.inactiveArea).add(this.samplesArea).parent().parent().get(0);
	
	$.getJSON( this.options.parser, {action:'load', Page: this.options.page}, function( oJSON ){ _builder.loadJSON( oJSON ) } );
}

BxDolPageBuilder.prototype.loadJSON = function( oJSON ) {
	var _builder = this;
	if( window.console) console.log( oJSON );
	
	if( !oJSON.active ||!oJSON.widths || !oJSON.inactive || !oJSON.samples || !oJSON.min_widths )
		return false;
	
	$(this.activeArea  ).html( '' );
	$(this.inactiveArea).html( '' );
	$(this.samplesArea ).html( '' );
	
	this.minWidths = oJSON.min_widths;
	
	var iColumns = 0;
	//this.aColumnsWidths = new Array();
	
	//active blocks
	for( var iColumn in oJSON.widths ) {
		var iWidth = oJSON.widths[iColumn];
		var aBlocks = oJSON.active[iColumn];
		this.drawColumn( iColumn, iWidth, aBlocks );
		
		//this.aColumnsWidths[iColumns] = parseInt( iWidth );
		
		iColumns ++;
	}
	
	this.checkAddColumn();
	
	//inactive blocks
	for( var iBlockID in oJSON.inactive ) {
		var sBlockCaption = oJSON.inactive[iBlockID];
		this.drawBlock( iBlockID, sBlockCaption, this.inactiveArea );
	}
	
	//inactive blocks
	for( var iBlockID in oJSON.samples ) {
		var sBlockCaption = oJSON.samples[iBlockID];
		this.drawBlock( iBlockID, sBlockCaption, this.samplesArea );
	}
	
	$(this.inactiveArea ).append( '<div class="buildBlockFake"></div> <div class="clear_both"></div>' );
	$(this.samplesArea  ).append( '<div class="clear_both"></div>' );
	
	this.initPageWidthSlider();
	this.initOtherPagesWidthSlider();
	this.initColsSlider();
	this.checkBlocksMaxWidths();
	this.activateSortables();
}

BxDolPageBuilder.prototype.initPageWidthSlider = function() {
	var _builder = this;
	var $slider = $( '#pageWidthSlider' );
	
	if( !$slider.length )
		return false;
	
	$slider.slider( {
		handle: 'div',
		change: function(e,s) {_builder.onWidthSliderStop(s)},
		slide:  function(e,s) {_builder.onWidthSliderMove(s)}

	} );
	
	var iCurSliderVal = this.width2slider( this.options.pageWidth );
	$slider.sliderMoveTo( iCurSliderVal );
	$( '#pageWidthValue' ).html( this.options.pageWidth );
}

BxDolPageBuilder.prototype.initOtherPagesWidthSlider = function() {
	var _builder = this;
	var $slider = $( '#pageWidthSlider1' );
	
	if( !$slider.length )
		return false;
	
	$slider.slider( {
		handle: 'div',
		change: function(e,s) {_builder.onOtherWidthSliderStop(s)},
		slide:  function(e,s) {_builder.onOtherWidthSliderMove(s)}

	} );
	
	var iCurSliderVal = this.width2slider( this.options.otherPagesWidth );
	$slider.sliderMoveTo( iCurSliderVal );
	$( '#pageWidthValue1' ).html( this.options.otherPagesWidth );
}

BxDolPageBuilder.prototype.width2slider = function( sCurWidth ) {
	if( sCurWidth == '100%' )
		return 100;
	
	var iCurWidth = parseInt( sCurWidth );
	return ( Math.round( ( ( iCurWidth - 774 ) * 90 ) / 826 ) + 5 );
}

BxDolPageBuilder.prototype.slider2width = function( iSliderVal ) {
	if( iSliderVal < 5 )
		return '774px';
	
	if( iSliderVal > 95 )
		return '100%';
	
	return ( ( parseInt( ( ( iSliderVal - 5 ) * 826 ) / 90 ) + 774 ) + 'px' );
}

BxDolPageBuilder.prototype.onWidthSliderStop = function(slider) {
	var _builder = this;
	
	//set current page width
	this.options.pageWidth = this.slider2width( slider.values );
	
	//submit page width
	$.post( this.options.parser, {
		action: 'savePageWidth',
		Page: this.options.page,
		width: this.options.pageWidth
	},
	function( sResponse ) {
		if( sResponse != 'OK' )
			alert( sResponse );
	} );
	
	//update columns headers
	$( '.buildColumn', this.activeArea ).each( function(iInd){
		_builder.setColumnHeader( this, (iInd + 1) );
	} );
	
	this.checkBlocksMaxWidths();
}

BxDolPageBuilder.prototype.onWidthSliderMove = function(slider) {
	var sCurPageWidth = this.slider2width( slider.values );
	$( '#pageWidthValue' ).html( sCurPageWidth );
}

BxDolPageBuilder.prototype.onOtherWidthSliderStop = function(slider) {
	var _builder = this;
	
	//set current page width
	this.options.otherPagesWidth = this.slider2width( slider.values );
	
	//submit page width
	$.post( this.options.parser, {
		action: 'saveOtherPagesWidth',
		Page: this.options.page,
		width: this.options.otherPagesWidth
	},
	function( sResponse ) {
		if( sResponse != 'OK' )
			alert( sResponse );
	} );
}

BxDolPageBuilder.prototype.onOtherWidthSliderMove = function(slider) {
	var sCurPageWidth = this.slider2width( slider.values );
	$( '#pageWidthValue1' ).html( sCurPageWidth );
}

BxDolPageBuilder.prototype.checkBlocksMaxWidths = function() {
	//remove alerts
	$( '.blockAlert' ).remove();
	
	if( this.options.pageWidth == '100%' )
		return ; //do not check
	
	for( var iBlockID in this.minWidths ) {
		var iBlockMinWidth = this.minWidths[iBlockID];
		
		var $block = $( '#buildBlock_' + iBlockID );
		var iColumnWidth = Math.round( parseInt( this.options.pageWidth ) * parseInt( $block.parent().parent().css( 'width' ) ) / 100 );
		if( iColumnWidth < iBlockMinWidth ) {
			$( '<img src="images/icons/alert.gif" class="blockAlert" />' )
			.appendTo( $block )
			.hover( 
				function(){ showFloatDesc( 'The column containing this block should be at least ' + iBlockMinWidth + ' px wide; narrower width may result in design corruption.' ); },
				function(){ hideFloatDesc(); }
			)
			.mousemove( function(e){ moveFloatDesc( e ) } );
		}
	}
}

BxDolPageBuilder.prototype.checkAddColumn = function() {
	var _builder = this;
	
	var iColumns = $('.buildColumn', this.activeArea).length;
	
	var $linksCont = $('#pageControls');
	var $myLink = $( '#addColumnLink', $linksCont );
	
	if( iColumns >= this.options.maxCols )
		$myLink.remove();
	else if( !$myLink.length ) {
		$( '<a href="#" id="addColumnLink">Add column</a>' )
		.click( function(){
			_builder.addColumn();
			return false;
		} )
		.appendTo( $linksCont );
	}
}

BxDolPageBuilder.prototype.addColumn = function() {
	this.destroySortables();
	this.drawColumn($('.buildColumn',this.activeArea).length, 0,{});
	this.checkAddColumn();
	this.activateSortables();
	this.reArrangeColumns();
}

BxDolPageBuilder.prototype.initColsSlider = function() {
	var iSliderValue = 0;
	var aSliderValues = new Array();
	var _builder = this;
	
	var $Columns = $( '.buildColumn', this.activeArea )
	var iColumns = $Columns.length;
	
	$( '#columnsSlider' ).sliderDestroy();
	
	if( iColumns < 2 )
		return; //dont insert
	
	var sSliderCode = '';
	for( var iSliderNum = 0; iSliderNum < (iColumns - 1); iSliderNum ++ ) {
		var iColWidth = parseInt( $Columns.eq(iSliderNum).css( 'width' ) );
		iSliderValue += iColWidth;
		aSliderValues[iSliderNum] = iSliderValue;
		
		sSliderCode += '<div></div>';
	}
	
	$(this.activeArea).append( sSliderCode );
	
	//init slider
	$( '#columnsSlider' ).html( sSliderCode )
	.slider( {
		handle: 'div',
		change: function(e,s) {_builder.onColsSliderStop(s)},
		slide:  function(e,s) {_builder.onColsSliderMove(s)}
	} );
	
	for( var iSliderNum = 0; iSliderNum < aSliderValues.length; iSliderNum ++ ) {
		var iSliderValue = aSliderValues[iSliderNum];
		
		$( '#columnsSlider' ).sliderMoveTo( iSliderValue, iSliderNum );
	}
}

BxDolPageBuilder.prototype.onColsSliderStop = function() {
	this.checkBlocksMaxWidths();
	this.submitWidths();
}

BxDolPageBuilder.prototype.onColsSliderMove = function(slider) {
	var _builder = this;
	var aValues = new Array();
	
	if( typeof slider.values == 'object' ) {
		var iCounter = 0;
		for( var iInd in slider.values )
			aValues[iCounter++] = slider.values[iInd];
	} else if( typeof slider.values == 'number' ) {
		aValues[0] = slider.values;
	}
	aValues[aValues.length] = 100;
	
	//console.log( aValues );
	
	var iMinusWidth = 0;
	$('.buildColumn', this.activeArea).each( function(iInd){
		var iNewWidth = aValues[iInd] - iMinusWidth;
		
		$(this).css( 'width', iNewWidth + '%' );
		_builder.setColumnHeader( this, (iInd+1) );
		
		iMinusWidth += iNewWidth;
	} );
}

BxDolPageBuilder.prototype.submit = function() {
	var _builder = this;
	
	var aColumns = new Array();
	//get columns
	$( '.buildColumn', this.activeArea ).each( function(){
		var iColumn = aColumns.length;
		
		aColumns[iColumn] = new Array();
		//get blocks
		$( '.buildBlock', this ).each( function(){
			var iItemID = parseInt( this.id.substr( 'buildBlock_'.length ) );
			aColumns[iColumn].push(iItemID);
		} );
		
		aColumns[iColumn] = aColumns[iColumn].join(',');
		
		iColumn ++;
	} );
	
	$.post(
		this.options.parser, {
			action: 'saveBlocks',
			Page: this.options.page,
			'columns[]': aColumns
		},
		function(sResponse){
			if( sResponse != 'OK' )
				alert(sResponse);
			
			_builder.submitWidths();
		}
	);
}

BxDolPageBuilder.prototype.submitWidths = function() {
	var aWidths = new Array();
	
	$( '.buildColumn', this.activeArea ).each( function(){
		aWidths[aWidths.length] = parseInt( $(this).css('width') );
	} );
	
	$.post(
		this.options.parser,
		{
			action:'saveColsWidths',
			Page: this.options.page,
			'widths[]': aWidths
		},
		function(sResponse){
			if( sResponse != 'OK' )
				alert(sResponse);
		}
	);
}

BxDolPageBuilder.prototype.setColumnHeader = function( parent, iNum, bIgnoreColsNum ) {
	var bIgnoreColsNum = bIgnoreColsNum || false;
	var _builder = this;
	
	var iPerWidth = parseInt( $(parent).css('width') );
	
	var sPixAdd = '';
	
	if( this.options.pageWidth.substr(-2) == 'px' ) {
		var iPixWidth = Math.round( ( parseInt( this.options.pageWidth ) * iPerWidth ) / 100 );
		sPixAdd = '/' + iPixWidth + 'px';
	}
	
	var $header = $('.buildColumnHeader', parent).html(
		'Column ' + iNum +
		' (' + iPerWidth + '%' + sPixAdd + ')'
	);
	
	if( bIgnoreColsNum || $('.buildColumn', this.activeArea).length > this.options.minCols ) {
		$header.append(
			' <a href="#" title="Delete" id="linkDelete">' +
				'<img src="images/cross.gif" alt="Delete" />' +
			'</a>'
		).children('a').click( function(){
			if( confirm( 'Do you really want to delete this column' ) ) {
				_builder.deleteColumn( parent );
			}
			return false;
		});
	}
}

BxDolPageBuilder.prototype.deleteColumn = function( column ) {
	$('.buildBlock', column).prependTo( this.inactiveArea );
	$(column).remove();
	
	this.checkAddColumn();
	this.reArrangeColumns();
}

BxDolPageBuilder.prototype.reArrangeColumns = function() {
	var _builder = this;
	var $columns = $('.buildColumn', this.activeArea);
	var iNewWidth = Math.floor( 100 / $columns.length );
	
	$columns.css( 'width', iNewWidth + '%' ).each( function( iInd ) {
		_builder.setColumnHeader( this, (iInd+1) );
	} );
	
	this.initColsSlider();
	this.submit();
}

BxDolPageBuilder.prototype.destroySortables = function() {
	if( this.oSIColumns )
		this.oSIColumns.destroy();
	
	if( this.oSIBlocks )
		this.oSIBlocks.destroy();
}

BxDolPageBuilder.prototype.activateSortables = function() {
	var _builder = this;
	
	// SI = SortableInstance
	this.oSIColumns = $(this.activeArea).sortable({
		items: '.buildColumn',
		hoverClass: 'buildHover',
		stop: function() { _builder.columnsStopSort(); }
	}).sortableInstance();
	
	this.oSIBlocks = $(this.eAllAreas).sortable({
		items: '.buildBlock,.buildBlockFake',
		hoverClass: 'buildHover',
		stop: function() { _builder.blocksStopSort(this); }
	}).sortableInstance();
	
}

BxDolPageBuilder.prototype.columnsStopSort = function( cycled ) {
	var _builder = this;
	
	if( cycled == undefined ) {
		setTimeout( function(){_builder.columnsStopSort(true)}, 600 );
		return ;
	}
	
	var iCounter = 0;
	var iSliderValue = 0;
	$('.buildColumn', this.activeArea).each( function(){
		iCounter ++;
		var iWidth = parseInt( $(this).css('width') );
		iSliderValue += iWidth;
		
		//alert( iSliderValue );
		
		//update slider
		$( '#columnsSlider', this.activeArea ).sliderMoveTo( iSliderValue, (iCounter - 1) );
		
		//update column header
		_builder.setColumnHeader( this, iCounter );
	} );
	
	this.submit();
}

BxDolPageBuilder.prototype.blocksStopSort = function( eDragged, cycled ) {
	var _builder = this;
	
	if( cycled == undefined ) {
		setTimeout( function(){_builder.blocksStopSort(eDragged, true)}, 600 );
		return ;
	}
	
	//check if the dragged element is sample
	if( $( '#' + eDragged.id, this.activeArea ).length ) { // if it is dragged to the active area
		var iBlockID = parseInt( eDragged.id.substr( 'buildBlock_'.length ) );
		$.post(
			this.options.parser,
			{
				action: 'checkNewBlock',
				Page: this.options.page,
				id: iBlockID
			},
			function( sResponse ) {
				if( sResponse == '' ) {
					_builder.submit();
				} else {
					var iNewBlockID = parseInt( sResponse );
					if( iNewBlockID )
						_builder.addBlock(iNewBlockID,eDragged);
					_builder.submit();
				}
			}
		);
	} else
		this.submit();
}

BxDolPageBuilder.prototype.addBlock = function( iNewID, eBefore ) {
	this.drawBlock( iNewID, $(eBefore).text(), this.samplesArea );
	
	$( '#buildBlock_' + iNewID, this.samplesArea ).insertBefore( eBefore );
	$( eBefore ).prependTo( this.samplesArea );
	
	this.destroySortables();
	this.activateSortables();
}

BxDolPageBuilder.prototype.drawColumn = function( iColumnNum, iWidth, aBlocks ) {
	$('div.clear_both',this.activeArea).remove();
	
	var $newColumn = $(
		'<div class="buildColumn" style="width:' + iWidth + '%;">' +
			'<div class="buildColumnCont">' +
				'<div class="buildColumnHeader"></div>' +
				'<div class="buildBlockFake"></div>' +
			'</div>' +
		'</div>'
	).appendTo(this.activeArea);
	
	this.setColumnHeader( $newColumn, iColumnNum, true );
	
	var eColumnCont = $( '.buildColumnCont', $newColumn ).get(0);
	
	for( var iBlockID in aBlocks ) {

		var sBlockCaption = aBlocks[iBlockID];
		this.drawBlock( iBlockID, sBlockCaption, eColumnCont );
	}
	
	$(this.activeArea).append( '<div class="clear_both"></div>' );
}

BxDolPageBuilder.prototype.drawBlock = function( iBlockID, sBlockCaption, eColumnCont ) {
	var _builder = this;
	
	$(
		'<div class="buildBlock" id="buildBlock_' + iBlockID + '">' +
			'<a href="#">' + sBlockCaption + '</a>' +
		'</div>'
	)
	.appendTo(eColumnCont)
	.children('a')
		.click( function() {
			_builder.openProperties( iBlockID );
			return false;
		} );
}

BxDolPageBuilder.prototype.openProperties = function( iBlockID ) {
	var _builder = this;
	
	$( '#editFormWrapper' ).show()
	.css({
		left  : this.getHorizScroll() - 30,
		top   : this.getVertScroll() - 30,
		width : document.body.clientWidth + 30,
		height: (window.innerHeight ? (window.innerHeight + 30) : screen.height)
	});
	
	$( '#editFormCont' )
	.html( '<img src="images/loading.gif" alt="Loading..." title="Loading..." />' )
	.load(
		this.options.parser,
		{
			action:'loadEditForm',
			Page: this.options.page,
			id: iBlockID
		},
		function() {
			var $form = $( 'form', this );
			
			$('#form_input_html', $form).each( function(){
				tinyMCE.execCommand('mceAddControl', false, 'form_input_html');
			} );
			
			$(':reset[name=Cancel]',$form).click( function(){
				$('#form_input_html',$form).each( function() {
					tinyMCE.execCommand('mceRemoveControl', false, 'form_input_html');
				} );
				
				$( '#editFormWrapper' ).hide();
				return false;
			} );
			
			$(':reset[name=Delete]',$form).click( function(){
				if( confirm( 'Are you sure want to delete this item?' ) ) {
					_builder.deleteBlock( iBlockID );
					$( '#editFormWrapper' ).hide();
				}
			});
			
			$form.ajaxForm( {
				beforeSubmit: function(){
					$('#form_input_html',$form).each( function() {
						tinyMCE.execCommand('mceRemoveControl', false, 'form_input_html');
					});
					
					return true;
				},
				success: function(sResponse){
					_builder.updateBlock( iBlockID, sResponse );
					$( '#editFormWrapper' ).hide();
				}
			} );
		}
	);
}

BxDolPageBuilder.prototype.deleteBlock = function( iBlockID ) {
	$( '#buildBlock_' + iBlockID ).remove();
	$.post( this.options.parser,{
		action: 'deleteBlock',
		Page: this.options.page,
		id: iBlockID
	} );
}

BxDolPageBuilder.prototype.updateBlock = function( iBlockID, sCaption ) {
	var _builder = this;
	
	$( '#buildBlock_' + iBlockID ).html( '<a href="#">' + sCaption + '</a>' )
	.children('a').click( function() {
		_builder.openProperties( iBlockID );
		return false;
	} );
}

BxDolPageBuilder.prototype.getHorizScroll = function() {
	if (navigator.appName == "Microsoft Internet Explorer")
		return document.documentElement.scrollLeft;
	else
		return window.pageXOffset;
}

BxDolPageBuilder.prototype.getVertScroll = function()
{
	if (navigator.appName == "Microsoft Internet Explorer")
		return document.documentElement.scrollTop;
	else
		return window.pageYOffset;
}
