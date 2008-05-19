package org.dwbn.userreg.action;

import java.util.List;
import java.util.Map;

import org.apache.struts2.interceptor.ParameterAware;
import org.dwbn.userreg.model.dwbn.Registration;
import org.dwbn.userreg.service.RegistrationService;

import com.opensymphony.xwork2.Action;
import com.opensymphony.xwork2.Preparable;

public class RegistrationAction implements Preparable, ParameterAware{
    private RegistrationService service;
    private Map parameters;    
    
    private List<Registration> regList;
    private Registration reg;
    private Integer id;

    public RegistrationAction( RegistrationService service ){
        this.service = service;
    }
    
    public void setParameters( Map parameters ){
    	this.parameters = parameters;
    }
    
    public Map getParameters(){
    	return this.parameters;
    }

    public String execute(){
   		this.regList = service.findAll();
        return Action.SUCCESS;    	
    }
    
    public String save() {
        this.service.save( reg );
        //@TODO ?
        this.reg = new Registration();
    	return execute();
    }
    
    public String remove() {
        service.remove( id );
        return execute();
    }
    
    public void changeStatusWaitingToConfirmed(){
    	service.changeStatusWaitingToConfirmed( id );
    }    

    public List<Registration> getRegistrationList(){
        return regList;
    }

    public Integer getId(){
        return id;
    }

    public void setId(Integer id) {
        this.id = id;
    }

    public void prepare() throws Exception{
        if (id != null)
        	reg = service.find( id );
    }

    public Registration getRegistration(){
        return reg;
    }

    public void setRegistration( Registration regi ){
        this.reg = regi;
    }
}