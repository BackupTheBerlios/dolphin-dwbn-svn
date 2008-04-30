package org.dwbn.userreg.model;

import java.sql.Date;

import javax.persistence.Entity;
import javax.persistence.Id;

@Entity		 
public class RegistrationConfirmed{
	@Id
	private Integer id;
	
	private Date regdate;
    private String firstName;
    private String lastName;
    private String sex;
    private String address;
    private String zip;
    private String city;
    private String country;
    private String phone;
    private String fax;
    private String email;
    private String preferredLanguage;
    private String age;
    private String homepage;
    private String homeCenter;
    private String friendCenter;
    private String friendOther;
    private String didFind;    
    private String tellUs;
    private boolean newsletterEnglish;
    private boolean newsletterGerman;
    private boolean streamingEnglish; 
	
	public Integer getId() {
	  return id;
	}
	
	public void setId(Integer id) {
	  this.id = id;
	}  	
	
	public Date getRegdate() {
		return regdate;
	}
	
	public void setRegdate(Date regdate) {
		this.regdate = regdate;
	}	
	
    public String getFirstName() {
        return firstName;
    }

    public void setFirstName( String firstName ){
        this.firstName = firstName;
    }
    
    public String getLastName(){
        return lastName;
    }

    public void setLastName( String lastName ){
        this.lastName = lastName;
    }

	public String getEmail() {
		return email;
	}

	public void setEmail(String email) {
		this.email = email;
	}
	
	public String getSex() {
		return sex;
	}

	public void setSex(String sex) {
		this.sex = sex;
	}

	public String getAddress() {
		return address;
	}

	public void setAddress(String address) {
		this.address = address;
	}

	public String getZip() {
		return zip;
	}

	public void setZip(String zip) {
		this.zip = zip;
	}

	public String getCity() {
		return city;
	}

	public void setCity(String city) {
		this.city = city;
	}

	public String getCountry() {
		return country;
	}

	public void setCountry(String country) {
		this.country = country;
	}

	public String getPhone() {
		return phone;
	}

	public void setPhone(String phone) {
		this.phone = phone;
	}

	public String getFax() {
		return fax;
	}

	public void setFax(String fax) {
		this.fax = fax;
	}

	public String getPreferredLanguage() {
		return preferredLanguage;
	}

	public void setPreferredLanguage(String preferredLanguage) {
		this.preferredLanguage = preferredLanguage;
	}

	public String getAge() {
		return age;
	}

	public void setAge(String age) {
		this.age = age;
	}

	public String getHomepage() {
		return homepage;
	}

	public void setHomepage(String homepage) {
		this.homepage = homepage;
	}

	public String getHomeCenter() {
		return homeCenter;
	}

	public void setHomeCenter(String homeCenter) {
		this.homeCenter = homeCenter;
	}

	public String getFriendCenter() {
		return friendCenter;
	}

	public void setFriendCenter(String friendCenter) {
		this.friendCenter = friendCenter;
	}

	public String getFriendOther() {
		return friendOther;
	}

	public void setFriendOther(String friendOther) {
		this.friendOther = friendOther;
	}

	public String getDidFind() {
		return didFind;
	}

	public void setDidFind(String didFind) {
		this.didFind = didFind;
	}

	public String getTellUs() {
		return tellUs;
	}

	public void setTellUs(String tellUs) {
		this.tellUs = tellUs;
	}

	public boolean isNewsletterEnglish() {
		return newsletterEnglish;
	}

	public void setNewsletterEnglish(boolean newsletterEnglish) {
		this.newsletterEnglish = newsletterEnglish;
	}

	public boolean isNewsletterGerman() {
		return newsletterGerman;
	}

	public void setNewsletterGerman(boolean newsletterGerman) {
		this.newsletterGerman = newsletterGerman;
	}

	public boolean isStreamingEnglish() {
		return streamingEnglish;
	}

	public void setStreamingEnglish(boolean streamingEnglish) {
		this.streamingEnglish = streamingEnglish;
	}
	
	public String toString(){
		return "Firstname: "+firstName+"\nLastname: "+lastName+"\nEmail: "+email;
	}
}
