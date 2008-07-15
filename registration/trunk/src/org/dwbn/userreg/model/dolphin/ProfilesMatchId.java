package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Embeddable;

/**
 * ProfilesMatchId generated by hbm2java
 */
@Embeddable
public class ProfilesMatchId implements java.io.Serializable {

	private int pid1;
	private int pid2;
	private byte percent;

	public ProfilesMatchId() {
	}

	public ProfilesMatchId(int pid1, int pid2, byte percent) {
		this.pid1 = pid1;
		this.pid2 = pid2;
		this.percent = percent;
	}

	@Column(name = "PID1", nullable = false)
	public int getPid1() {
		return this.pid1;
	}

	public void setPid1(int pid1) {
		this.pid1 = pid1;
	}

	@Column(name = "PID2", nullable = false)
	public int getPid2() {
		return this.pid2;
	}

	public void setPid2(int pid2) {
		this.pid2 = pid2;
	}

	@Column(name = "Percent", nullable = false)
	public byte getPercent() {
		return this.percent;
	}

	public void setPercent(byte percent) {
		this.percent = percent;
	}

	public boolean equals(Object other) {
		if ((this == other))
			return true;
		if ((other == null))
			return false;
		if (!(other instanceof ProfilesMatchId))
			return false;
		ProfilesMatchId castOther = (ProfilesMatchId) other;

		return (this.getPid1() == castOther.getPid1())
				&& (this.getPid2() == castOther.getPid2())
				&& (this.getPercent() == castOther.getPercent());
	}

	public int hashCode() {
		int result = 17;

		result = 37 * result + this.getPid1();
		result = 37 * result + this.getPid2();
		result = 37 * result + this.getPercent();
		return result;
	}

}