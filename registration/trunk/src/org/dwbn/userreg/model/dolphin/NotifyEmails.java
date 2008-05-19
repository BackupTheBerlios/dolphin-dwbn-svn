package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import static javax.persistence.GenerationType.IDENTITY;
import javax.persistence.Id;
import javax.persistence.Table;

/**
 * NotifyEmails generated by hbm2java
 */
@Entity
@Table(name = "NotifyEmails", catalog = "dolphin")
public class NotifyEmails implements java.io.Serializable {

	private Integer id;
	private String name;
	private String email;
	private String emailFlag;
	private String emailText;

	public NotifyEmails() {
	}

	public NotifyEmails(String name, String email, String emailFlag,
			String emailText) {
		this.name = name;
		this.email = email;
		this.emailFlag = emailFlag;
		this.emailText = emailText;
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

	@Column(name = "Name", nullable = false, length = 64)
	public String getName() {
		return this.name;
	}

	public void setName(String name) {
		this.name = name;
	}

	@Column(name = "Email", nullable = false, length = 128)
	public String getEmail() {
		return this.email;
	}

	public void setEmail(String email) {
		this.email = email;
	}

	@Column(name = "EmailFlag", nullable = false, length = 11)
	public String getEmailFlag() {
		return this.emailFlag;
	}

	public void setEmailFlag(String emailFlag) {
		this.emailFlag = emailFlag;
	}

	@Column(name = "EmailText", nullable = false, length = 8)
	public String getEmailText() {
		return this.emailText;
	}

	public void setEmailText(String emailText) {
		this.emailText = emailText;
	}

}
