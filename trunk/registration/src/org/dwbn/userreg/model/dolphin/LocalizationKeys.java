package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import static javax.persistence.GenerationType.IDENTITY;
import javax.persistence.Id;
import javax.persistence.Table;
import javax.persistence.UniqueConstraint;

/**
 * LocalizationKeys generated by hbm2java
 */
@Entity
@Table(name = "LocalizationKeys", catalog = "dolphin", uniqueConstraints = @UniqueConstraint(columnNames = "Key"))
public class LocalizationKeys implements java.io.Serializable {

	private Short id;
	private byte idcategory;
	private String key;

	public LocalizationKeys() {
	}

	public LocalizationKeys(byte idcategory, String key) {
		this.idcategory = idcategory;
		this.key = key;
	}

	@Id
	@GeneratedValue(strategy = IDENTITY)
	@Column(name = "ID", unique = true, nullable = false)
	public Short getId() {
		return this.id;
	}

	public void setId(Short id) {
		this.id = id;
	}

	@Column(name = "IDCategory", nullable = false)
	public byte getIdcategory() {
		return this.idcategory;
	}

	public void setIdcategory(byte idcategory) {
		this.idcategory = idcategory;
	}

	@Column(name = "Key", unique = true, nullable = false)
	public String getKey() {
		return this.key;
	}

	public void setKey(String key) {
		this.key = key;
	}

}
