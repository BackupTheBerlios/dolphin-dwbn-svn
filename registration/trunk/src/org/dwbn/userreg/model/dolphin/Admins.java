package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Id;
import javax.persistence.Table;

/**
 * Admins generated by hbm2java
 */
@Entity
@Table(name = "Admins", catalog = "dolphin")
public class Admins implements java.io.Serializable {

	private String name;
	private String password;

	public Admins() {
	}

	public Admins(String name, String password) {
		this.name = name;
		this.password = password;
	}

	@Id
	@Column(name = "Name", unique = true, nullable = false, length = 10)
	public String getName() {
		return this.name;
	}

	public void setName(String name) {
		this.name = name;
	}

	@Column(name = "Password", nullable = false, length = 32)
	public String getPassword() {
		return this.password;
	}

	public void setPassword(String password) {
		this.password = password;
	}

}
