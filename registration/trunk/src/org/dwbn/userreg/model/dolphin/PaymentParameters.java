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
 * PaymentParameters generated by hbm2java
 */
@Entity
@Table(name = "PaymentParameters", catalog = "dolphin", uniqueConstraints = @UniqueConstraint(columnNames = {
		"IDProvider", "Name" }))
public class PaymentParameters implements java.io.Serializable {

	private Integer id;
	private short idprovider;
	private String name;
	private String caption;
	private String type;
	private String extra;
	private String value;
	private boolean changable;

	public PaymentParameters() {
	}

	public PaymentParameters(short idprovider, String name, String type,
			String value, boolean changable) {
		this.idprovider = idprovider;
		this.name = name;
		this.type = type;
		this.value = value;
		this.changable = changable;
	}

	public PaymentParameters(short idprovider, String name, String caption,
			String type, String extra, String value, boolean changable) {
		this.idprovider = idprovider;
		this.name = name;
		this.caption = caption;
		this.type = type;
		this.extra = extra;
		this.value = value;
		this.changable = changable;
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

	@Column(name = "IDProvider", nullable = false)
	public short getIdprovider() {
		return this.idprovider;
	}

	public void setIdprovider(short idprovider) {
		this.idprovider = idprovider;
	}

	@Column(name = "Name", nullable = false)
	public String getName() {
		return this.name;
	}

	public void setName(String name) {
		this.name = name;
	}

	@Column(name = "Caption")
	public String getCaption() {
		return this.caption;
	}

	public void setCaption(String caption) {
		this.caption = caption;
	}

	@Column(name = "Type", nullable = false, length = 6)
	public String getType() {
		return this.type;
	}

	public void setType(String type) {
		this.type = type;
	}

	@Column(name = "Extra", length = 65535)
	public String getExtra() {
		return this.extra;
	}

	public void setExtra(String extra) {
		this.extra = extra;
	}

	@Column(name = "Value", nullable = false, length = 65535)
	public String getValue() {
		return this.value;
	}

	public void setValue(String value) {
		this.value = value;
	}

	@Column(name = "Changable", nullable = false)
	public boolean isChangable() {
		return this.changable;
	}

	public void setChangable(boolean changable) {
		this.changable = changable;
	}

}