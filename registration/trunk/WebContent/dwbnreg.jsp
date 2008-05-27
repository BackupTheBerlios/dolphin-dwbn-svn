<%@ taglib prefix="s" uri="/struts-tags"%>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/registration.css" />
	</head>
	<body>
		<b>Registration Data</b>
		<s:form action="saveRegistration" validate="true" cssStyle="width:555px">
		    <s:textfield id="id" name="registration.id" cssStyle="display:none"/>
		    
			<s:textfield id="firstName" label="%{getText('registration.firstname')}" size="30" value="Jan" name="registration.firstName"/>
			<s:textfield id="lastName" label="%{getText('registration.lastname')}" size="30" value="Haevecker" name="registration.lastName"/>

			<!-- s:select label="Sex" 
				name="registration.sex" 
				headerKey="1"
				headerValue="-- Please Select --"
				list="{'Female','Male'}"
			/-->

		    <s:select
		        label="%{getText('registration.sex')}"		    
		        name="registration.sex" 
		        headerKey="1"
				headerValue="-- Please Select --"		        
		        list="sexList"
			/>
			
			<s:textfield id="address" label="%{getText('registration.address')}" size="30" name="registration.address"/>
			<s:textfield id="zip" label="%{getText('registration.zip')}" size="30" name="registration.zip"/>
			<s:textfield id="city" label="%{getText('registration.city')}" value="Ernsthofen" size="30" name="registration.city"/>
			
		    <s:select label="%{getText('registration.country')}" 
			    name="registration.country" 
			    headerKey="1"
			    headerValue="-- Please Select --"
			    list="countryList" 
		    />	
		    
			<s:textfield id="phone" label="%{getText('registration.phone')}" size="30" name="registration.phone"/>
			<s:textfield id="fax" label="%{getText('registration.fax')}" size="30" name="registration.fax"/>
			<s:textfield id="email" label="%{getText('registration.email')}" size="30" value="heavy@webbbbbbb.dedddd" name="registration.email"/>				

		    <s:select label="%{getText('registration.preferredLanguage')}" 
			    name="registration.preferredLanguage" 
			    headerKey="0"
			    headerValue="-- Please Select --"
			    list="LanguageList"
		    />
			
		    <s:select label="%{getText('registration.age')}"
			    name="registration.age" 
			    headerKey="1"
			    headerValue="-- Please Select --"
			    list="{'0-10','11-20','21-30','31-40','41-50','51-60','61-70','71-80','More than 81 years old'}"
		    />

			<s:textfield id="homepage" label="%{getText('registration.homepage')}" size="30" value="http://www.jane.de" name="registration.homepage"/>

		    <s:select label="%{getText('registration.homeCenter')}" 
			    name="registration.homeCenter" 
			    headerKey="1"
			    headerValue="-- Please Select --"
			    list="centerList"
		    />
		    
			<b><s:label value="%{getText('registration.recommendationText')}" /></b>
			
			<s:textfield id="friendcenter" label="%{getText('registration.friendCenter')}" size="30" value="Gunther" name="registration.friendCenter"/>
			
			<s:textfield id="friendother" label="%{getText('registration.friendOther')}" size="30" value="Peter" name="registration.friendOther"/>
			
		    <s:select label="%{getText('registration.didFind')}" 
			    name="registration.didFind" 
			    headerKey="1"
			    headerValue="-- Please Select --"
			    list = "{'Just surfed on in!','Link from a website','Word of mouth','Search engine','Personal friend','Mailing list','News group','Advertisement/brochure','None of this'}"
		    />		
		    
			<s:textarea id="tellus" label="%{getText('registration.tellUs')}" cols="30" rows="8" name="registration.tellUs" />
			
			<s:checkbox label="%{getText('registration.newsletter')}" value="true" name="registration.newsletter" />
			
			<s:checkbox label="%{getText('registration.streaming')}" value="true" name="registration.streaming" />
		    		
			
			<!--
			<s:checkboxlist name="foo" list="#{'01':'Newsletter in english', '02':'Newsletter in german'}"/>
			
		    list="{'01':'0-10','02':'11-20','03':'21-30','04':'31-40','05':'41-50','06':'51-60','7':'61-70','08':'71-80','09':'More than 81 years old'}"
			
			<s:autocompleter theme="simple" list="centersList" name="centername"/>

			Please name two people who already receive DWBN News who can recommend you:
			-->

			<s:submit />
		</s:form>
		
		<s:debug /> 
	</body>
</html>

