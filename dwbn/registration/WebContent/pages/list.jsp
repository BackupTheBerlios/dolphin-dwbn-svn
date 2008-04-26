<%@ taglib prefix="s" uri="/struts-tags"%>

<s:if test="persons.size > 0">
    <p>Persons</p>
	<table>
		<s:iterator value="persons">
			<tr id="row_<s:property value="id"/>">
				<td>
					<s:property value="firstName" />
				</td>
				<td>
					<s:property value="lastName" />
				</td>
				<td>
					<s:property value="email" />
				</td>				
				<td>
					<s:url id="removeUrl" action="remove">
						<s:param name="id" value="id" />
					</s:url>
					<s:a href="%{removeUrl}" theme="ajax" targets="persons">Remove</s:a>
					<s:a id="a_%{id}" theme="ajax" notifyTopics="/edit">Edit</s:a>
				</td>
			</tr>
		</s:iterator>
	</table>
</s:if>

<s:if test="registrationsWaiting.size > 0">
    <p>Registrations</p>
	<table>
		<s:iterator value="registrationsWaiting">
			<tr id="row_<s:property value="id"/>">
				<td>
					<s:property value="firstName" />
				</td>
				<td>
					<s:property value="lastName" />
				</td>
				<td>
					<s:property value="email" />
				</td>				
				<td>
					<s:url id="removeUrl" action="removeRegistrationWaiting">
						<s:param name="id" value="id" />
						<!-- 
						<s:param name="status" value="waiting" />
						 -->
					</s:url>
					<s:a href="%{removeUrl}" theme="ajax" targets="registrationsWaiting">Remove</s:a>
					<s:a id="a_%{id}" theme="ajax" notifyTopics="/edit">Edit</s:a>
				</td>
			</tr>
		</s:iterator>
	</table>
</s:if>


