package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import java.util.Date;
import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import static javax.persistence.GenerationType.IDENTITY;
import javax.persistence.Id;
import javax.persistence.Table;
import javax.persistence.Temporal;
import javax.persistence.TemporalType;

/**
 * ClassifiedsAdvertisementsMedia generated by hbm2java
 */
@Entity
@Table(name = "ClassifiedsAdvertisementsMedia", catalog = "dolphin")
public class ClassifiedsAdvertisementsMedia implements java.io.Serializable {

	private Integer mediaId;
	private int mediaProfileId;
	private String mediaType;
	private String mediaFile;
	private Date mediaDate;

	public ClassifiedsAdvertisementsMedia() {
	}

	public ClassifiedsAdvertisementsMedia(int mediaProfileId, String mediaType,
			String mediaFile, Date mediaDate) {
		this.mediaProfileId = mediaProfileId;
		this.mediaType = mediaType;
		this.mediaFile = mediaFile;
		this.mediaDate = mediaDate;
	}

	@Id
	@GeneratedValue(strategy = IDENTITY)
	@Column(name = "MediaID", unique = true, nullable = false)
	public Integer getMediaId() {
		return this.mediaId;
	}

	public void setMediaId(Integer mediaId) {
		this.mediaId = mediaId;
	}

	@Column(name = "MediaProfileID", nullable = false)
	public int getMediaProfileId() {
		return this.mediaProfileId;
	}

	public void setMediaProfileId(int mediaProfileId) {
		this.mediaProfileId = mediaProfileId;
	}

	@Column(name = "MediaType", nullable = false, length = 6)
	public String getMediaType() {
		return this.mediaType;
	}

	public void setMediaType(String mediaType) {
		this.mediaType = mediaType;
	}

	@Column(name = "MediaFile", nullable = false, length = 50)
	public String getMediaFile() {
		return this.mediaFile;
	}

	public void setMediaFile(String mediaFile) {
		this.mediaFile = mediaFile;
	}

	@Temporal(TemporalType.TIMESTAMP)
	@Column(name = "MediaDate", nullable = false, length = 0)
	public Date getMediaDate() {
		return this.mediaDate;
	}

	public void setMediaDate(Date mediaDate) {
		this.mediaDate = mediaDate;
	}

}
