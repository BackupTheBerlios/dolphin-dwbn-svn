package org.dwbn.userreg.model.dwbn;

import java.net.URL;
import java.sql.Date;
import java.util.Arrays;
import java.util.List;

import javax.persistence.Entity;
import javax.persistence.EnumType;
import javax.persistence.Enumerated;
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;
import javax.persistence.OneToOne;

@Entity
public class Registration {
	private Integer id;

	public enum Sex {
		Male, Female 
	}

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
	private Language preferredLanguage;
	private String age;
	private URL homepage;
	private Center homeCenter;
	private String friendCenter;
	private String friendOther;
	private String didFind;
	private String tellUs;
	private boolean newsletter;
	private boolean streaming;

	// Not injected from outside
	public Registration() {
		this.regdate = new Date((new java.util.Date()).getTime() /*- 2 * 24 * 3600 * 1000*/);
	}

	// Injected from outside (index.jsp --> list.jsp)
	@Id
	@GeneratedValue(strategy=GenerationType.AUTO)
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

	public void setFirstName(String firstName) {
		this.firstName = firstName;
	}

	public String getLastName() {
		return lastName;
	}

	public void setLastName(String lastName) {
		this.lastName = lastName;
	}

	public String getEmail() {
		return email;
	}

	public void setEmail(String email) {
		this.email = email;
	}

	@Enumerated(EnumType.ORDINAL)
	public Sex getSex() {
		return sex;
	}
	
	public void setSex(Sex sex) {
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

	@OneToOne
	public Language getPreferredLanguage() {
		return preferredLanguage;
	}

	public void setPreferredLanguage(Language preferredLanguage) {
		this.preferredLanguage = preferredLanguage;
	}

	public String getAge() {
		return age;
	}

	public void setAge(String age) {
		this.age = age;
	}

	public URL getHomepage() {
		return homepage;
	}

	public void setHomepage(URL homepage) {
		this.homepage = homepage;
	}

	@OneToOne
	public Center getHomeCenter() {
		return homeCenter;
	}

	public void setHomeCenter(Center homeCenter) {
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

	public boolean isNewsletter() {
		return newsletter;
	}

	public void setNewsletter(boolean newsletter) {
		this.newsletter = newsletter;
	}

	public boolean isStreaming() {
		return streaming;
	}

	public void setStreaming(boolean streaming) {
		this.streaming = streaming;
	}

	public String toString() {
		return "Firstname: " + firstName + "\nLastname: " + lastName
				+ "\nEmail: " + email;
	}
}
