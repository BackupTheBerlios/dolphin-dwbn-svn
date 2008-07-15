package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import java.util.Date;
import javax.persistence.Column;
import javax.persistence.Embeddable;

/**
 * ProfileMemLevelsId generated by hbm2java
 */
@Embeddable
public class ProfileMemLevelsId implements java.io.Serializable {

	private long idmember;
	private short idlevel;
	private Date dateStarts;

	public ProfileMemLevelsId() {
	}

	public ProfileMemLevelsId(long idmember, short idlevel, Date dateStarts) {
		this.idmember = idmember;
		this.idlevel = idlevel;
		this.dateStarts = dateStarts;
	}

	@Column(name = "IDMember", nullable = false)
	public long getIdmember() {
		return this.idmember;
	}

	public void setIdmember(long idmember) {
		this.idmember = idmember;
	}

	@Column(name = "IDLevel", nullable = false)
	public short getIdlevel() {
		return this.idlevel;
	}

	public void setIdlevel(short idlevel) {
		this.idlevel = idlevel;
	}

	@Column(name = "DateStarts", nullable = false, length = 0)
	public Date getDateStarts() {
		return this.dateStarts;
	}

	public void setDateStarts(Date dateStarts) {
		this.dateStarts = dateStarts;
	}

	public boolean equals(Object other) {
		if ((this == other))
			return true;
		if ((other == null))
			return false;
		if (!(other instanceof ProfileMemLevelsId))
			return false;
		ProfileMemLevelsId castOther = (ProfileMemLevelsId) other;

		return (this.getIdmember() == castOther.getIdmember())
				&& (this.getIdlevel() == castOther.getIdlevel())
				&& ((this.getDateStarts() == castOther.getDateStarts()) || (this
						.getDateStarts() != null
						&& castOther.getDateStarts() != null && this
						.getDateStarts().equals(castOther.getDateStarts())));
	}

	public int hashCode() {
		int result = 17;

		result = 37 * result + (int) this.getIdmember();
		result = 37 * result + this.getIdlevel();
		result = 37
				* result
				+ (getDateStarts() == null ? 0 : this.getDateStarts()
						.hashCode());
		return result;
	}

}