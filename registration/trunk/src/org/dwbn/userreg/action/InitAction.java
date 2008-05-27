package org.dwbn.userreg.action;

import java.util.Arrays;
import java.util.List;

import javax.persistence.EntityManager;
import javax.persistence.PersistenceContext;
import javax.persistence.Query;

import org.dwbn.userreg.model.dwbn.Registration;

import com.opensymphony.xwork2.ActionSupport;

//@Transactional test svn
public class InitAction extends ActionSupport{
	
    private EntityManager em;
    
    private List sexList;    
    private List<String> countriesList;
    private List<String> languagesList;
    private List<String> centersList;    
    
    private EntityManager getEntityManager() {
        return em;
    }
    
    @PersistenceContext
    public void setEntityManager( EntityManager em ){
        this.em = em;
    }
    
    public String execute() throws Exception{ 
    	this.initCountryList();
    	this.initLanguageList();
    	this.initCenterList();    	
    	this.initSexList();    	
        return SUCCESS; 
    } 
    
    @SuppressWarnings("unchecked")
    public void initCountryList() {
    	Query query = getEntityManager().createQuery("SELECT c FROM Country AS c");
        this.countriesList = query.getResultList();
    }    
 
    @SuppressWarnings("unchecked")
    public void initLanguageList() {
    	Query query = getEntityManager().createQuery( "SELECT l FROM Language AS l" );
        this.languagesList = query.getResultList();
    } 
    
    @SuppressWarnings("unchecked")
    public void initCenterList() {
    	Query query = getEntityManager().createQuery( "SELECT c FROM Center AS c" );
        this.centersList = query.getResultList();
    } 
    
    @SuppressWarnings("unchecked")
    public void initSexList() {
        this.sexList = Arrays.asList( Registration.Sex.values() );
    }     
    
    public List<String> getCountryList(){
        return this.countriesList;
    }  
    
    public List<String> getLanguageList(){
        return this.languagesList;
    }
    
    public List<String> getCenterList(){
        return this.centersList;
    }  
    
    public List getSexList(){
        return this.sexList;
    }     
}
