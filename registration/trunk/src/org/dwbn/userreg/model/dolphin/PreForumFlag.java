package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.AttributeOverride;
import javax.persistence.AttributeOverrides;
import javax.persistence.Column;
import javax.persistence.EmbeddedId;
import javax.persistence.Entity;
import javax.persistence.Table;

/**
 * PreForumFlag generated by hbm2java
 */
@Entity
@Table(name = "pre_forum_flag", catalog = "dolphin")
public class PreForumFlag implements java.io.Serializable {

	private PreForumFlagId id;
	private int when;

	public PreForumFlag() {
	}

	public PreForumFlag(PreForumFlagId id, int when) {
		this.id = id;
		this.when = when;
	}

	@EmbeddedId
	@AttributeOverrides( {
			@AttributeOverride(name = "user", column = @Column(name = "user", nullable = false, length = 16)),
			@AttributeOverride(name = "topicId", column = @Column(name = "topic_id", nullable = false)) })
	public PreForumFlagId getId() {
		return this.id;
	}

	public void setId(PreForumFlagId id) {
		this.id = id;
	}

	@Column(name = "when", nullable = false)
	public int getWhen() {
		return this.when;
	}

	public void setWhen(int when) {
		this.when = when;
	}

}
