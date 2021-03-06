package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import static javax.persistence.GenerationType.IDENTITY;
import javax.persistence.Id;
import javax.persistence.Table;

/**
 * RayImMessages generated by hbm2java
 */
@Entity
@Table(name = "RayImMessages", catalog = "dolphin")
public class RayImMessages implements java.io.Serializable {

	private Integer id;
	private int contactId;
	private String message;
	private String style;
	private String type;
	private int when;

	public RayImMessages() {
	}

	public RayImMessages(int contactId, String message, String style,
			String type, int when) {
		this.contactId = contactId;
		this.message = message;
		this.style = style;
		this.type = type;
		this.when = when;
	}

	@Id
	@GeneratedValue(strategy = IDENTITY)
	@Column(name = "ID", unique = true, nullable = false)
	public Integer getId() {
		return this.id;
	}

	public void setId(Integer id) {
		this.id = id;
	}

	@Column(name = "ContactID", nullable = false)
	public int getContactId() {
		return this.contactId;
	}

	public void setContactId(int contactId) {
		this.contactId = contactId;
	}

	@Column(name = "Message", nullable = false, length = 65535)
	public String getMessage() {
		return this.message;
	}

	public void setMessage(String message) {
		this.message = message;
	}

	@Column(name = "Style", nullable = false, length = 65535)
	public String getStyle() {
		return this.style;
	}

	public void setStyle(String style) {
		this.style = style;
	}

	@Column(name = "Type", nullable = false, length = 5)
	public String getType() {
		return this.type;
	}

	public void setType(String type) {
		this.type = type;
	}

	@Column(name = "When", nullable = false)
	public int getWhen() {
		return this.when;
	}

	public void setWhen(int when) {
		this.when = when;
	}

}
