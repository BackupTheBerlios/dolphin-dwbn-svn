package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Id;
import javax.persistence.Table;

/**
 * RayChatProfiles generated by hbm2java
 */
@Entity
@Table(name = "RayChatProfiles", catalog = "dolphin")
public class RayChatProfiles implements java.io.Serializable {

	private String id;
	private String banned;
	private String type;
	private String smileset;

	public RayChatProfiles() {
	}

	public RayChatProfiles(String id, String banned, String type,
			String smileset) {
		this.id = id;
		this.banned = banned;
		this.type = type;
		this.smileset = smileset;
	}

	@Id
	@Column(name = "ID", unique = true, nullable = false, length = 20)
	public String getId() {
		return this.id;
	}

	public void setId(String id) {
		this.id = id;
	}

	@Column(name = "Banned", nullable = false, length = 5)
	public String getBanned() {
		return this.banned;
	}

	public void setBanned(String banned) {
		this.banned = banned;
	}

	@Column(name = "Type", nullable = false, length = 5)
	public String getType() {
		return this.type;
	}

	public void setType(String type) {
		this.type = type;
	}

	@Column(name = "Smileset", nullable = false)
	public String getSmileset() {
		return this.smileset;
	}

	public void setSmileset(String smileset) {
		this.smileset = smileset;
	}

}