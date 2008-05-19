package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.AttributeOverride;
import javax.persistence.AttributeOverrides;
import javax.persistence.Column;
import javax.persistence.EmbeddedId;
import javax.persistence.Entity;
import javax.persistence.Table;
import javax.persistence.UniqueConstraint;

/**
 * ProfilesTrack generated by hbm2java
 */
@Entity
@Table(name = "ProfilesTrack", catalog = "dolphin", uniqueConstraints = @UniqueConstraint(columnNames = {
		"Member", "Profile" }))
public class ProfilesTrack implements java.io.Serializable {

	private ProfilesTrackId id;

	public ProfilesTrack() {
	}

	public ProfilesTrack(ProfilesTrackId id) {
		this.id = id;
	}

	@EmbeddedId
	@AttributeOverrides( {
			@AttributeOverride(name = "member", column = @Column(name = "Member", nullable = false)),
			@AttributeOverride(name = "profile", column = @Column(name = "Profile", nullable = false)),
			@AttributeOverride(name = "arrived", column = @Column(name = "Arrived", nullable = false, length = 0)),
			@AttributeOverride(name = "hide", column = @Column(name = "Hide", nullable = false)) })
	public ProfilesTrackId getId() {
		return this.id;
	}

	public void setId(ProfilesTrackId id) {
		this.id = id;
	}

}
