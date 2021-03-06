package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Embeddable;

/**
 * GrpForumFlagId generated by hbm2java
 */
@Embeddable
public class GrpForumFlagId implements java.io.Serializable {

	private String user;
	private int topicId;

	public GrpForumFlagId() {
	}

	public GrpForumFlagId(String user, int topicId) {
		this.user = user;
		this.topicId = topicId;
	}

	@Column(name = "user", nullable = false, length = 16)
	public String getUser() {
		return this.user;
	}

	public void setUser(String user) {
		this.user = user;
	}

	@Column(name = "topic_id", nullable = false)
	public int getTopicId() {
		return this.topicId;
	}

	public void setTopicId(int topicId) {
		this.topicId = topicId;
	}

	public boolean equals(Object other) {
		if ((this == other))
			return true;
		if ((other == null))
			return false;
		if (!(other instanceof GrpForumFlagId))
			return false;
		GrpForumFlagId castOther = (GrpForumFlagId) other;

		return ((this.getUser() == castOther.getUser()) || (this.getUser() != null
				&& castOther.getUser() != null && this.getUser().equals(
				castOther.getUser())))
				&& (this.getTopicId() == castOther.getTopicId());
	}

	public int hashCode() {
		int result = 17;

		result = 37 * result
				+ (getUser() == null ? 0 : this.getUser().hashCode());
		result = 37 * result + this.getTopicId();
		return result;
	}

}
