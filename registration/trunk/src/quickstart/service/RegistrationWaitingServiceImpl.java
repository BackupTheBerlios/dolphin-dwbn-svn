package quickstart.service;

import java.sql.Date;
import java.util.List;

import javax.persistence.EntityManager;
import javax.persistence.PersistenceContext;
import javax.persistence.Query;

import org.springframework.transaction.annotation.Transactional;

import quickstart.model.RegistrationConfirmed;
import quickstart.model.RegistrationWaiting;

@Transactional
public class RegistrationWaitingServiceImpl implements RegistrationWaitingService{
    private EntityManager em;

    @PersistenceContext
    public void setEntityManager(EntityManager em){
        this.em = em;
    }
    
    private EntityManager getEntityManager(){
        return em;
    }

    @SuppressWarnings("unchecked")
    public List<RegistrationWaiting> findAllWaiting(){
    	Query query = getEntityManager().createQuery( "SELECT r FROM RegistrationWaiting r" );
        return query.getResultList();
    }

    public void saveWaiting(RegistrationWaiting rw){
        if( rw.getId() == null ){
            //Create a new DB entry
            em.persist( rw );
        } 
        else{
            //Update DB entry
            em.merge( rw );
        }
    }

    public void removeWaiting( int id ) {
    	RegistrationWaiting rw = findWaiting( id );
        if( rw != null ){
            em.remove( rw );
        }
    }

    public RegistrationWaiting findWaiting( int id ){
        return em.find( RegistrationWaiting.class, id );
    }    
    
    //After clicking on the confirmattion link, transfer registration from table waiting 
    //to table confirmed.
    public void transferWaitingToConfirmed( int id ){
    	RegistrationWaiting rw = findWaiting( id );
    	
    	if( rw != null ){
    		em.remove( rw );
    		
    		RegistrationConfirmed rc = new RegistrationConfirmed();
    		
    		//Set the mandatory attributes
    		rc.setId( rw.getId() );
    		rc.setRegdate( rw.getRegdate() );
    		rc.setFirstName( rw.getFirstName() );
    		rc.setLastName( rw.getLastName() );
    		rc.setSex( rw.getSex() );
    		rc.setCity( rw.getCity() );
    		rc.setCountry( rw.getCountry() );
    		rc.setEmail( rw.getEmail() );
    		rc.setPreferredLanguage( rw.getPreferredLanguage() );   		
    		rc.setAge( rw.getAge() );    		
    		rc.setHomeCenter( rw.getHomeCenter() );   		
    		rc.setFriendCenter( rw.getFriendCenter() );
    		rc.setFriendOther( rw.getFriendOther() );
    		rc.setNewsletterEnglish( rw.isNewsletterEnglish() );
    		rc.setNewsletterGerman( rw.isNewsletterGerman() );
    		rc.setStreamingEnglish( rw.isStreamingEnglish() );	
    		
    		//Set the optional attributes
    		rc.setAddress( rw.getAddress() );
    		rc.setZip( rw.getZip() );
    		rc.setPhone( rw.getPhone() );
    		rc.setFax( rw.getFax() );
    		rc.setHomepage( rw.getHomepage() );
    		rc.setDidFind( rw.getDidFind() );   
    		rc.setTellUs( rw.getTellUs() );
    		
    		try{
    			em.persist( rc );
    		}catch( Exception e ){
    			//@TODO log.info(e.getMessage());
    	    }    		
    	}
    	else{
    		//@TODO Registration not found!
    	}
    }    
    
    @SuppressWarnings("unchecked")
    public void cleanUp( /*@TODO int days*/ ){
    	Query query = getEntityManager().createNativeQuery( 
    			"DELETE FROM RegistrationWaiting WHERE regdate = '"+ new Date( (new java.util.Date()).getTime() ) + "'" );
        query.executeUpdate();        
    }    
}