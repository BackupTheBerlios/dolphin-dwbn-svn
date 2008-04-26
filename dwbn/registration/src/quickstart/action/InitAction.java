package quickstart.action;

import java.util.List;

import javax.persistence.EntityManager;
import javax.persistence.PersistenceContext;
import javax.persistence.Query;

import com.opensymphony.xwork2.ActionSupport;

//@Transactional
public class InitAction extends ActionSupport{
	
    private EntityManager em;
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
    	this.initCountries();
    	this.initLanguages();
    	this.initCenters();    	
        return SUCCESS; 
    } 
    
    @SuppressWarnings("unchecked")
    public void initCountries() {
    	Query query = getEntityManager().createNativeQuery( "SELECT c.country FROM countries c" );
        this.countriesList = query.getResultList();
    }    
 
    @SuppressWarnings("unchecked")
    public void initLanguages() {
    	Query query = getEntityManager().createNativeQuery( "SELECT l.language FROM languages l" );
        this.languagesList = query.getResultList();
    } 
    
    @SuppressWarnings("unchecked")
    public void initCenters() {
    	Query query = getEntityManager().createNativeQuery( "SELECT c.center FROM centers c" );
        this.centersList = query.getResultList();
    } 
    
    public List<String> getCountriesList(){
        return this.countriesList;
    }  
    
    public List<String> getLanguagesList(){
        return this.languagesList;
    }
    
    public List<String> getCentersList(){
        return this.centersList;
    }     
}
