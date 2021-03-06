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
 * ProfilesSettings generated by hbm2java
 */
@Entity
@Table(name = "ProfilesSettings", catalog = "dolphin", uniqueConstraints = @UniqueConstraint(columnNames = "IDMember"))
public class ProfilesSettings implements java.io.Serializable {

	private ProfilesSettingsId id;

	public ProfilesSettings() {
	}

	public ProfilesSettings(ProfilesSettingsId id) {
		this.id = id;
	}

	@EmbeddedId
	@AttributeOverrides( {
			@AttributeOverride(name = "idmember", column = @Column(name = "IDMember", unique = true, nullable = false)),
			@AttributeOverride(name = "backgroundFilename", column = @Column(name = "BackgroundFilename", length = 40)),
			@AttributeOverride(name = "backgroundColor", column = @Column(name = "BackgroundColor", length = 60)),
			@AttributeOverride(name = "fontColor", column = @Column(name = "FontColor", length = 60)),
			@AttributeOverride(name = "fontSize", column = @Column(name = "FontSize", length = 60)),
			@AttributeOverride(name = "fontFamily", column = @Column(name = "FontFamily", length = 60)),
			@AttributeOverride(name = "status", column = @Column(name = "Status", length = 20)) })
	public ProfilesSettingsId getId() {
		return this.id;
	}

	public void setId(ProfilesSettingsId id) {
		this.id = id;
	}

}
