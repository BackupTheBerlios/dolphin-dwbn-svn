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
 * LocalizationLanguages generated by hbm2java
 */
@Entity
@Table(name = "LocalizationLanguages", catalog = "dolphin", uniqueConstraints = @UniqueConstraint(columnNames = "Name"))
public class LocalizationLanguages implements java.io.Serializable {

	private Byte id;
	private String name;
	private String flag;
	private String title;

	public LocalizationLanguages() {
	}

	public LocalizationLanguages(String name, String flag, String title) {
		this.name = name;
		this.flag = flag;
		this.title = title;
	}

	@Id
	@GeneratedValue(strategy = IDENTITY)
	@Column(name = "ID", unique = true, nullable = false)
	public Byte getId() {
		return this.id;
	}

	public void setId(Byte id) {
		this.id = id;
	}

	@Column(name = "Name", unique = true, nullable = false, length = 5)
	public String getName() {
		return this.name;
	}

	public void setName(String name) {
		this.name = name;
	}

	@Column(name = "Flag", nullable = false, length = 2)
	public String getFlag() {
		return this.flag;
	}

	public void setFlag(String flag) {
		this.flag = flag;
	}

	@Column(name = "Title", nullable = false)
	public String getTitle() {
		return this.title;
	}

	public void setTitle(String title) {
		this.title = title;
	}

}
