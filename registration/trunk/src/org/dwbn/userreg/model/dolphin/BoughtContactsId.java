package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Embeddable;

/**
 * BoughtContactsId generated by hbm2java
 */
@Embeddable
public class BoughtContactsId implements java.io.Serializable {

	private long idbuyer;
	private long idcontact;

	public BoughtContactsId() {
	}

	public BoughtContactsId(long idbuyer, long idcontact) {
		this.idbuyer = idbuyer;
		this.idcontact = idcontact;
	}

	@Column(name = "IDBuyer", nullable = false)
	public long getIdbuyer() {
		return this.idbuyer;
	}

	public void setIdbuyer(long idbuyer) {
		this.idbuyer = idbuyer;
	}

	@Column(name = "IDContact", nullable = false)
	public long getIdcontact() {
		return this.idcontact;
	}

	public void setIdcontact(long idcontact) {
		this.idcontact = idcontact;
	}

	public boolean equals(Object other) {
		if ((this == other))
			return true;
		if ((other == null))
			return false;
		if (!(other instanceof BoughtContactsId))
			return false;
		BoughtContactsId castOther = (BoughtContactsId) other;

		return (this.getIdbuyer() == castOther.getIdbuyer())
				&& (this.getIdcontact() == castOther.getIdcontact());
	}

	public int hashCode() {
		int result = 17;

		result = 37 * result + (int) this.getIdbuyer();
		result = 37 * result + (int) this.getIdcontact();
		return result;
	}

}
