package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import java.util.Date;
import javax.persistence.AttributeOverride;
import javax.persistence.AttributeOverrides;
import javax.persistence.Column;
import javax.persistence.EmbeddedId;
import javax.persistence.Entity;
import javax.persistence.Table;
import javax.persistence.Temporal;
import javax.persistence.TemporalType;

/**
 * Vkisses generated by hbm2java
 */
@Entity
@Table(name = "VKisses", catalog = "dolphin")
public class Vkisses implements java.io.Serializable {

	private VkissesId id;
	private short number;
	private Date arrived;
	private String new_;

	public Vkisses() {
	}

	public Vkisses(VkissesId id, short number, Date arrived, String new_) {
		this.id = id;
		this.number = number;
		this.arrived = arrived;
		this.new_ = new_;
	}

	@EmbeddedId
	@AttributeOverrides( {
			@AttributeOverride(name = "id", column = @Column(name = "ID", nullable = false)),
			@AttributeOverride(name = "member", column = @Column(name = "Member", nullable = false)) })
	public VkissesId getId() {
		return this.id;
	}

	public void setId(VkissesId id) {
		this.id = id;
	}

	@Column(name = "Number", nullable = false)
	public short getNumber() {
		return this.number;
	}

	public void setNumber(short number) {
		this.number = number;
	}

	@Temporal(TemporalType.DATE)
	@Column(name = "Arrived", nullable = false, length = 0)
	public Date getArrived() {
		return this.arrived;
	}

	public void setArrived(Date arrived) {
		this.arrived = arrived;
	}

	@Column(name = "New", nullable = false, length = 2)
	public String getNew_() {
		return this.new_;
	}

	public void setNew_(String new_) {
		this.new_ = new_;
	}

}
