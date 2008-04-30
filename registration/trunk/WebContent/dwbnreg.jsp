<%@ taglib prefix="s" uri="/struts-tags"%>
<html>
	<head>
	</head>
	<body>
		<b>Registration Data</b>
		<s:form action="saveRegistrationWaiting" validate="true" cssStyle="width:500px">
		    <s:textfield id="id" name="registrationWaiting.id" cssStyle="display:none"/>
			<s:textfield id="firstName" label="First Name" name="registrationWaiting.firstName"/>
			<s:textfield id="lastName" label="Last Name" name="registrationWaiting.lastName"/>

			<s:select label="Sex" 
				name="registrationWaiting.sex" 
				headerKey="1"
				headerValue="-- Please Select --"
				list="{'Female','Male'}"
			/>

			<s:textfield id="address" label="Address (*)" name="registrationWaiting.address"/>
			<s:textfield id="zip" label="Zip (*)" name="registrationWaiting.zip"/>
			<s:textfield id="city" label="City" name="registrationWaiting.city"/>
			
		    <s:select label="Select Country" 
			    name="registrationWaiting.country" 
			    headerKey="1"
			    headerValue="-- Please Select --"
			    list="countryList" 
		    />				
			
			<s:textfield id="phone" label="Phone (*)" name="registrationWaiting.phone"/>
			<s:textfield id="fax" label="Fax (*)" name="registrationWaiting.fax"/>
			<s:textfield id="email" label="Email" name="registrationWaiting.email"/>				

		    <s:select label="Preferred language" 
			    name="registrationWaiting.preferredLanguage" 
			    headerKey="1"
			    headerValue="-- Please Select --"
			    list="LanguageList"
		    />
			
		    <s:select label="Age" 
			    name="registrationWaiting.age" 
			    headerKey="1"
			    headerValue="-- Please Select --"
			    list="{'0-10','11-20','21-30','31-40','41-50','51-60','61-70','71-80','More than 81 years old'}"
		    />

			<s:textfield id="homepage" label="Homepage (*)" value="http://" name="registrationWaiting.homepage"/>

		    <s:select label="Which center do you visit" 
			    name="registrationWaiting.homeCenter" 
			    headerKey="1"
			    headerValue="-- Please Select --"
			    list="centerList"
		    />
		    
			<b><s:label key="Please name two people who already receive DWBN News who can recommend you" /></b>
			
			<s:textfield id="friendcenter" label="From your center ..." name="registrationWaiting.friendCenter"/>
			
			<s:textfield id="friendother" label="...or other Dharma friend" name="registrationWaiting.friendOther"/>
			
		    <s:select label="How did you find us? (*)" 
			    name="registrationWaiting.didFind" 
			    headerKey="1"
			    headerValue="-- Please Select --"
			    list = "{'Just surfed on in!','Link from a website','Word of mouth','Search engine','Personal friend','Mailing list','News group','Advertisement/brochure','None of this'}"
		    />		
		    
			<s:textarea id="tellus" label="Tell us something about you (*)" cols="30" rows="8" name="registrationWaiting.tellUs" />
			
			<s:checkbox label="Newsletter in English" value="true" name="registrationWaiting.newsletterEnglish" />
			
			<s:checkbox label="Newsletter in German" value="false" name="registrationWaiting.newsletterGerman" />
			
			<s:checkbox label="Streaming events in English" value="true" name="registrationWaiting.streamingEnglish" />
		    		
			
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

