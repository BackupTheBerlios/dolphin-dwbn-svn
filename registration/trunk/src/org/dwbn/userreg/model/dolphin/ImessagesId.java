package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import java.util.Date;
import javax.persistence.Column;
import javax.persistence.Embeddable;

/**
 * ImessagesId generated by hbm2java
 */
@Embeddable
public class ImessagesId implements java.io.Serializable {

	private long idfrom;
	private long idto;
	private Date when;
	private String msg;

	public ImessagesId() {
	}

	public ImessagesId(long idfrom, long idto, Date when, String msg) {
		this.idfrom = idfrom;
		this.idto = idto;
		this.when = when;
		this.msg = msg;
	}

	@Column(name = "IDFrom", nullable = false)
	public long getIdfrom() {
		return this.idfrom;
	}

	public void setIdfrom(long idfrom) {
		this.idfrom = idfrom;
	}

	@Column(name = "IDTo", nullable = false)
	public long getIdto() {
		return this.idto;
	}

	public void setIdto(long idto) {
		this.idto = idto;
	}

	@Column(name = "When", nullable = false, length = 0)
	public Date getWhen() {
		return this.when;
	}

	public void setWhen(Date when) {
		this.when = when;
	}

	@Column(name = "Msg", nullable = false)
	public String getMsg() {
		return this.msg;
	}

	public void setMsg(String msg) {
		this.msg = msg;
	}

	public boolean equals(Object other) {
		if ((this == other))
			return true;
		if ((other == null))
			return false;
		if (!(other instanceof ImessagesId))
			return false;
		ImessagesId castOther = (ImessagesId) other;

		return (this.getIdfrom() == castOther.getIdfrom())
				&& (this.getIdto() == castOther.getIdto())
				&& ((this.getWhen() == castOther.getWhen()) || (this.getWhen() != null
						&& castOther.getWhen() != null && this.getWhen()
						.equals(castOther.getWhen())))
				&& ((this.getMsg() == castOther.getMsg()) || (this.getMsg() != null
						&& castOther.getMsg() != null && this.getMsg().equals(
						castOther.getMsg())));
	}

	public int hashCode() {
		int result = 17;

		result = 37 * result + (int) this.getIdfrom();
		result = 37 * result + (int) this.getIdto();
		result = 37 * result
				+ (getWhen() == null ? 0 : this.getWhen().hashCode());
		result = 37 * result
				+ (getMsg() == null ? 0 : this.getMsg().hashCode());
		return result;
	}

}
