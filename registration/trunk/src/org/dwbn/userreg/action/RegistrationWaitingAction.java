package org.dwbn.userreg.action;

import java.util.List;
import java.util.Map;

import org.apache.struts2.interceptor.ParameterAware;
import org.dwbn.userreg.model.Registration;
import org.dwbn.userreg.model.RegistrationConfirmed;
import org.dwbn.userreg.model.RegistrationWaiting;
import org.dwbn.userreg.service.RegistrationWaitingService;


import com.opensymphony.xwork2.Action;
import com.opensymphony.xwork2.Preparable;

public class RegistrationWaitingAction implements Preparable, ParameterAware{
    private RegistrationWaitingService service;
    private Map parameters;    
    
    private List<RegistrationWaiting> registrationsWaiting;
    private RegistrationWaiting registrationWaiting;
    private Integer id;

    public RegistrationWaitingAction( RegistrationWaitingService service ){
        this.service = service;
    }
    
    public void setParameters( Map parameters ){
    	this.parameters = parameters;
    }
    
    public Map getParameters(){
    	return this.parameters;
    }

    public String execute(){
   		this.registrationsWaiting = service.findAllWaiting();
        return Action.SUCCESS;    	
    }
    
    public String save() {
        this.service.saveWaiting( registrationWaiting );
        //@TODO ?
        this.registrationWaiting = new RegistrationWaiting();
    	return execute();
    }
    
    public String remove() {
        service.removeWaiting(id);
        return execute();
    }
    
    public void transfer(){
    	service.transferWaitingToConfirmed( id );
    }    

    public List<RegistrationWaiting> getRegistrationsWaiting(){
        return registrationsWaiting;
    }

    public Integer getId(){
        return id;
    }

    public void setId(Integer id) {
        this.id = id;
    }

    public void prepare() throws Exception{
        if (id != null)
        	registrationWaiting = service.findWaiting( id );
    }

    public RegistrationWaiting getRegistrationWaiting(){
        return registrationWaiting;
    }

    public void setRegistrationWaiting( RegistrationWaiting registrationWaiting ){
        this.registrationWaiting = registrationWaiting;
    }
}