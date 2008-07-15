package org.dwbn.userreg.model.dwbn;

import java.net.URL;
import java.util.Date;

import javax.persistence.Table;

@Table(catalog = "dwbn")
public class DWBNSubscriber {
	
	public enum Sex {
		FEMALE, MALE
	}
	
	Integer ID; // Primärschlüssel N
	String Lastname; // J
	String Name; // J
	Country Country; // Kommt aus einer anderen Tabelle J
	String City; // Wohnort J
	Region Region; // Kommt aus einer anderen Tabelle N
	String EMail1; // J
	String EMail2; // N
	URL URL; // J
	boolean VIP; // N
	boolean DWBNNews; // Soll newsletter englisch bekommen? J
	boolean DWBNNewsGerman; // Soll newsletter deutsch bekommen? J
	boolean DWBNStreaming; // Soll Streaming bekommen? J
	boolean DWBNVirtualSangha; // Soll VS bekommen? J
	Date Subscription; // Aufnahmedatum ?
	Date LastUpdate; // Wann wurde der Datensatz zuletzt geändert? N
	Date CancelDate; // ? N
	String RefPerson1; // Referenzperson 1 J
	String RefPerson2; // Referenzperson 2 J
	Center VisitedCenter; // Kommt aus einer anderen Tabelle J
	long EMailForCenter; // ? N
	long RefOrganisation; // ? N
	long AgeClass; // Kommt aus einer anderen Tabelle J
	Sex Sex; // Kommt aus einer anderen Tabelle J
	String Comment; // J
	String OtherSkills; // J
	Language PreferredLanguage; // Kommt aus einer anderen Tabelle J
	long SecurityKey; // ? N
	String Key; // ? ?
	long ICQ; // J
	String SMSto; // ?
	long Status; // ? N
	String MobilPhone; // J
	Date LastChanged; // gibt ja schon LastUpdate? N

}
