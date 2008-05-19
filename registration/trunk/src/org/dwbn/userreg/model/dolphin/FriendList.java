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
 * FriendList generated by hbm2java
 */
@Entity
@Table(name = "FriendList", catalog = "dolphin", uniqueConstraints = @UniqueConstraint(columnNames = {
		"ID", "Profile" }))
public class FriendList implements java.io.Serializable {

	private FriendListId id;

	public FriendList() {
	}

	public FriendList(FriendListId id) {
		this.id = id;
	}

	@EmbeddedId
	@AttributeOverrides( {
			@AttributeOverride(name = "id", column = @Column(name = "ID", nullable = false)),
			@AttributeOverride(name = "profile", column = @Column(name = "Profile", nullable = false)),
			@AttributeOverride(name = "check", column = @Column(name = "Check", nullable = false)) })
	public FriendListId getId() {
		return this.id;
	}

	public void setId(FriendListId id) {
		this.id = id;
	}

}
