package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.AttributeOverride;
import javax.persistence.AttributeOverrides;
import javax.persistence.Column;
import javax.persistence.EmbeddedId;
import javax.persistence.Entity;
import javax.persistence.Table;

/**
 * ProfilesMatch generated by hbm2java
 */
@Entity
@Table(name = "ProfilesMatch", catalog = "dolphin")
public class ProfilesMatch implements java.io.Serializable {

	private ProfilesMatchId id;

	public ProfilesMatch() {
	}

	public ProfilesMatch(ProfilesMatchId id) {
		this.id = id;
	}

	@EmbeddedId
	@AttributeOverrides( {
			@AttributeOverride(name = "pid1", column = @Column(name = "PID1", nullable = false)),
			@AttributeOverride(name = "pid2", column = @Column(name = "PID2", nullable = false)),
			@AttributeOverride(name = "percent", column = @Column(name = "Percent", nullable = false)) })
	public ProfilesMatchId getId() {
		return this.id;
	}

	public void setId(ProfilesMatchId id) {
		this.id = id;
	}

}
