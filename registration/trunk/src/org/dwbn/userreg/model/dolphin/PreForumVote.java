package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.AttributeOverride;
import javax.persistence.AttributeOverrides;
import javax.persistence.Column;
import javax.persistence.EmbeddedId;
import javax.persistence.Entity;
import javax.persistence.Table;

/**
 * PreForumVote generated by hbm2java
 */
@Entity
@Table(name = "pre_forum_vote", catalog = "dolphin")
public class PreForumVote implements java.io.Serializable {

	private PreForumVoteId id;
	private int voteWhen;
	private byte votePoint;

	public PreForumVote() {
	}

	public PreForumVote(PreForumVoteId id, int voteWhen, byte votePoint) {
		this.id = id;
		this.voteWhen = voteWhen;
		this.votePoint = votePoint;
	}

	@EmbeddedId
	@AttributeOverrides( {
			@AttributeOverride(name = "userName", column = @Column(name = "user_name", nullable = false, length = 16)),
			@AttributeOverride(name = "postId", column = @Column(name = "post_id", nullable = false)) })
	public PreForumVoteId getId() {
		return this.id;
	}

	public void setId(PreForumVoteId id) {
		this.id = id;
	}

	@Column(name = "vote_when", nullable = false)
	public int getVoteWhen() {
		return this.voteWhen;
	}

	public void setVoteWhen(int voteWhen) {
		this.voteWhen = voteWhen;
	}

	@Column(name = "vote_point", nullable = false)
	public byte getVotePoint() {
		return this.votePoint;
	}

	public void setVotePoint(byte votePoint) {
		this.votePoint = votePoint;
	}

}