package quickstart.interceptor;

import java.util.Map;
import java.util.Properties;

import javax.mail.Message;
import javax.mail.MessagingException;
import javax.mail.Transport;
import javax.mail.internet.AddressException;
import javax.mail.internet.InternetAddress;
import javax.mail.internet.MimeMessage;

import org.apache.struts2.interceptor.ParameterAware;

import quickstart.action.RegistrationWaitingAction;
import quickstart.model.Registration;
import quickstart.model.RegistrationWaiting;

import com.opensymphony.xwork2.Action;
import com.opensymphony.xwork2.ActionInvocation;
import com.opensymphony.xwork2.interceptor.AbstractInterceptor;

public class EmailInterceptor extends AbstractInterceptor /*implements ParameterAware*/{
    private Map parameters;   
    private RegistrationWaiting registrationWaiting;  
    
    /*public void setParameters( Map parameters ){
    	this.parameters = parameters;
    }
    
    public Map getParameters(){
    	return this.parameters;
    }*/
    
    public String intercept( ActionInvocation invocation ) throws Exception{
    	//In this if-clause the next interceptor is invoked. If no more interceptors are on the call stack,
    	//the action is invoked. The order in which the interceptors are invoked is provided in struts.xml.
    	//After the action is called all interceptors on the call stack are called again, but in reverse order.
    	//To understand recursion, you must understand recursion!
    	if( !invocation.isExecuted() ){
    		registrationWaiting = ((RegistrationWaitingAction) invocation.getAction()).getRegistrationWaiting();
    		invocation.invoke();
    	}

  		System.out.println("Email wird versendet!");
  		
    	//After the action is called this code is executed, since the recursion is "on its way back".
        sendEmail( registrationWaiting, "smtp.web.de", "25", "noreply", "peter.theissen", "blondine69" );
    	
    	return Action.SUCCESS;
    }
    
    
    protected void sendEmail( RegistrationWaiting rW, String host, String port, String sender, String user, String password ){
    	try{
    		Properties props = new Properties();
    		props.put("mail.smtp.host", "smtp.web.de");
    		props.put("mail.smtp.auth", "true");
    		javax.mail.Session mailSession = javax.mail.Session.getInstance( props );
    		  
    		MimeMessage message = new MimeMessage( mailSession );
    		
    		InternetAddress from = new InternetAddress( "peter.theissen@web.de" );
    		message.setFrom( from );
    		
    		InternetAddress to = new InternetAddress( rW.getEmail() ); 
    		message.addRecipient(Message.RecipientType.TO, to);
    		
    		message.setSubject("Test from new & fresh DWBN Registration.");
        	//message.setText( "Dear "+ rW.getFirstName() + " " + rW.getLastName() + "\n http://127.0.0.1:8080/dwbnservlet/confirm?email="+ rW.getEmail() );
        	message.setText( "http://localhost:8080/QuickstartMI/transfer.action?id="+rW.getId() );
        	
        	System.out.println("*************************************************");
        	System.out.println( "http://localhost:8080/QuickstartMI/transfer.action?id="+rW.getId() );
        	System.out.println("*************************************************");
    		 
    		Transport tr = mailSession.getTransport("smtp");
    		tr.connect( host, user, password );
    		tr.sendMessage(message, message.getAllRecipients());
    		tr.close();
    	}
    	catch( AddressException e ){
    	}
    	catch( MessagingException e ){
    	}		
    }
}


/* @TODO
  
Set s = empmap.entrySet();
for(Iterator i = s.iterator();i.hasNext();){
 Map.Entry me = (Map.Entry)i.next();
 System.out.println(me.getKey() + " : " + me.getValue()); 
}

for (Map<String,String> entry: this.parameters.entrySet()){
	   System.out.println(
	           entry.getKey() + " : " + entry.getValue()); 
	  }
	 }
*/


/*
//sendEmailOld( "smtp.web.de", "25", "noreply", "peter.theissen", "blondine69" );
protected void sendEmailOld( String host, String port, String sender, String user, String password ){
    //sendEmailOld( "smtp.web.de", "25", "noreply", "peter.theissen", "blondine69" );
    try{
      Properties props = new Properties();
      props.put("mail.smtp.host", "smtp.web.de");
      props.put("mail.smtp.auth", "true");
      javax.mail.Session mailSession = javax.mail.Session.getInstance( props );

      MimeMessage message = new MimeMessage( mailSession );

      InternetAddress from = new InternetAddress( "peter.theissen@web.de" );
      message.setFrom( from );
      InternetAddress to = new InternetAddress( "peter.theissen@web.de" );
      message.addRecipient(Message.RecipientType.TO, to);
      message.setSubject("Test from new DWBN registration.");
        message.setText( "http://127.0.0.1:8080/dwbnservlet/confirm?email=" );

      Transport tr = mailSession.getTransport("smtp");
      tr.connect( host, user, password );
      tr.sendMessage(message, message.getAllRecipients());
      tr.close();
      //out.println( "Transport closed!" );
    }
    catch( AddressException e ){
      //out.println( "AddressException" );
    }
    catch( MessagingException e ){
      //out.println( "MessagingException" );
    }   
}
*/