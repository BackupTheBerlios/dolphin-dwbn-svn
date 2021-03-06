package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Embeddable;

/**
 * GmusicRatingId generated by hbm2java
 */
@Embeddable
public class GmusicRatingId implements java.io.Serializable {

	private int galId;
	private int galRatingCount;
	private int galRatingSum;

	public GmusicRatingId() {
	}

	public GmusicRatingId(int galId, int galRatingCount, int galRatingSum) {
		this.galId = galId;
		this.galRatingCount = galRatingCount;
		this.galRatingSum = galRatingSum;
	}

	@Column(name = "gal_id", unique = true, nullable = false)
	public int getGalId() {
		return this.galId;
	}

	public void setGalId(int galId) {
		this.galId = galId;
	}

	@Column(name = "gal_rating_count", nullable = false)
	public int getGalRatingCount() {
		return this.galRatingCount;
	}

	public void setGalRatingCount(int galRatingCount) {
		this.galRatingCount = galRatingCount;
	}

	@Column(name = "gal_rating_sum", nullable = false)
	public int getGalRatingSum() {
		return this.galRatingSum;
	}

	public void setGalRatingSum(int galRatingSum) {
		this.galRatingSum = galRatingSum;
	}

	public boolean equals(Object other) {
		if ((this == other))
			return true;
		if ((other == null))
			return false;
		if (!(other instanceof GmusicRatingId))
			return false;
		GmusicRatingId castOther = (GmusicRatingId) other;

		return (this.getGalId() == castOther.getGalId())
				&& (this.getGalRatingCount() == castOther.getGalRatingCount())
				&& (this.getGalRatingSum() == castOther.getGalRatingSum());
	}

	public int hashCode() {
		int result = 17;

		result = 37 * result + this.getGalId();
		result = 37 * result + this.getGalRatingCount();
		result = 37 * result + this.getGalRatingSum();
		return result;
	}

}
