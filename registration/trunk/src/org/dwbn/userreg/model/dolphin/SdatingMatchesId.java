package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Embeddable;

/**
 * SdatingMatchesId generated by hbm2java
 */
@Embeddable
public class SdatingMatchesId implements java.io.Serializable {

	private long idchooser;
	private long idchosen;

	public SdatingMatchesId() {
	}

	public SdatingMatchesId(long idchooser, long idchosen) {
		this.idchooser = idchooser;
		this.idchosen = idchosen;
	}

	@Column(name = "IDChooser", nullable = false)
	public long getIdchooser() {
		return this.idchooser;
	}

	public void setIdchooser(long idchooser) {
		this.idchooser = idchooser;
	}

	@Column(name = "IDChosen", nullable = false)
	public long getIdchosen() {
		return this.idchosen;
	}

	public void setIdchosen(long idchosen) {
		this.idchosen = idchosen;
	}

	public boolean equals(Object other) {
		if ((this == other))
			return true;
		if ((other == null))
			return false;
		if (!(other instanceof SdatingMatchesId))
			return false;
		SdatingMatchesId castOther = (SdatingMatchesId) other;

		return (this.getIdchooser() == castOther.getIdchooser())
				&& (this.getIdchosen() == castOther.getIdchosen());
	}

	public int hashCode() {
		int result = 17;

		result = 37 * result + (int) this.getIdchooser();
		result = 37 * result + (int) this.getIdchosen();
		return result;
	}

}
