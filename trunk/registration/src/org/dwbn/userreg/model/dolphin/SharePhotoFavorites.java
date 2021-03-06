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
 * SharePhotoFavorites generated by hbm2java
 */
@Entity
@Table(name = "sharePhotoFavorites", catalog = "dolphin")
public class SharePhotoFavorites implements java.io.Serializable {

	private SharePhotoFavoritesId id;
	private Date favDate;

	public SharePhotoFavorites() {
	}

	public SharePhotoFavorites(SharePhotoFavoritesId id, Date favDate) {
		this.id = id;
		this.favDate = favDate;
	}

	@EmbeddedId
	@AttributeOverrides( {
			@AttributeOverride(name = "medId", column = @Column(name = "medID", nullable = false)),
			@AttributeOverride(name = "userId", column = @Column(name = "userID", nullable = false)) })
	public SharePhotoFavoritesId getId() {
		return this.id;
	}

	public void setId(SharePhotoFavoritesId id) {
		this.id = id;
	}

	@Temporal(TemporalType.TIMESTAMP)
	@Column(name = "favDate", nullable = false, length = 0)
	public Date getFavDate() {
		return this.favDate;
	}

	public void setFavDate(Date favDate) {
		this.favDate = favDate;
	}

}
