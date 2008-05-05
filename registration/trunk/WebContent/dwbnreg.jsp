<%@ taglib prefix="s" uri="/struts-tags"%>
<html>
	<head>
	</head>
	<body>
		<b>Registration Data</b>
		<s:form action="saveRegistration" validate="true" cssStyle="width:500px">
		    <s:textfield id="id" name="registration.id" cssStyle="display:none"/>
			<s:textfield id="firstName" label="First Name" name="registration.firstName"/>
			<s:textfield id="lastName" label="Last Name" name="registration.lastName"/>

			<s:select label="Sex" 
				name="registration.sex" 
				headerKey="1"
				headerValue="-- Please Select --"
				list="{'Female','Male'}"
			/>

			<s:textfield id="address" label="Address (*)" name="registration.address"/>
			<s:textfield id="zip" label="Zip (*)" name="registration.zip"/>
			<s:textfield id="city" label="City" name="registration.city"/>
			
		    <s:select label="Select Country" 
			    name="registration.country" 
			    headerKey="1"
			    headerValue="-- Please Select --"
			    list="countryList" 
		    />				
			
			<s:textfield id="phone" label="Phone (*)" name="registration.phone"/>
			<s:textfield id="fax" label="Fax (*)" name="registration.fax"/>
			<s:textfield id="email" label="Email" name="registration.email"/>				

		    <s:select label="Preferred language" 
			    name="registration.preferredLanguage" 
			    headerKey="1"
			    headerValue="-- Please Select --"
			    list="LanguageList"
		    />
			
		    <s:select label="Age" 
			    name="registration.age" 
			    headerKey="1"
			    headerValue="-- Please Select --"
			    list="{'0-10','11-20','21-30','31-40','41-50','51-60','61-70','71-80','More than 81 years old'}"
		    />

			<s:textfield id="homepage" label="Homepage (*)" value="http://" name="registration.homepage"/>

		    <s:select label="Which center do you visit" 
			    name="registration.homeCenter" 
			    headerKey="1"
			    headerValue="-- Please Select --"
			    list="centerList"
		    />
		    
			<b><s:label key="Please name two people who already receive DWBN News who can recommend you" /></b>
			
			<s:textfield id="friendcenter" label="From your center ..." name="registration.friendCenter"/>
			
			<s:textfield id="friendother" label="...or other Dharma friend" name="registration.friendOther"/>
			
		    <s:select label="How did you find us? (*)" 
			    name="registration.didFind" 
			    headerKey="1"
			    headerValue="-- Please Select --"
			    list = "{'Just surfed on in!','Link from a website','Word of mouth','Search engine','Personal friend','Mailing list','News group','Advertisement/brochure','None of this'}"
		    />		
		    
			<s:textarea id="tellus" label="Tell us something about you (*)" cols="30" rows="8" name="registration.tellUs" />
			
			<s:checkbox label="Newsletter in English" value="true" name="registration.newsletterEnglish" />
			
			<s:checkbox label="Newsletter in German" value="false" name="registration.newsletterGerman" />
			
			<s:checkbox label="Streaming events in English" value="true" name="registration.streamingEnglish" />
		    		
			
			<!--
			<s:checkboxlist name="foo" list="#{'01':'Newsletter in english', '02':'Newsletter in german'}"/>
			
		    list="{'01':'0-10','02':'11-20','03':'21-30','04':'31-40','05':'41-50','06':'51-60','7':'61-70','08':'71-80','09':'More than 81 years old'}"
			
			<s:autocompleter theme="simple" list="centersList" name="centername"/>

			Please name two people who already receive DWBN News who can recommend you:
			-->

			<s:submit />
		</s:form>
	</body>
</html>

