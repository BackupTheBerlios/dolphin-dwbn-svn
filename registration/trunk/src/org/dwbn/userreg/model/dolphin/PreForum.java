package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import static javax.persistence.GenerationType.IDENTITY;
import javax.persistence.Id;
import javax.persistence.Table;

/**
 * PreForum generated by hbm2java
 */
@Entity
@Table(name = "pre_forum", catalog = "dolphin")
public class PreForum implements java.io.Serializable {

	private Integer forumId;
	private String forumUri;
	private int catId;
	private String forumTitle;
	private String forumDesc;
	private int forumPosts;
	private int forumTopics;
	private int forumLast;
	private String forumType;

	public PreForum() {
	}

	public PreForum(String forumUri, int catId, String forumDesc,
			int forumPosts, int forumTopics, int forumLast, String forumType) {
		this.forumUri = forumUri;
		this.catId = catId;
		this.forumDesc = forumDesc;
		this.forumPosts = forumPosts;
		this.forumTopics = forumTopics;
		this.forumLast = forumLast;
		this.forumType = forumType;
	}

	public PreForum(String forumUri, int catId, String forumTitle,
			String forumDesc, int forumPosts, int forumTopics, int forumLast,
			String forumType) {
		this.forumUri = forumUri;
		this.catId = catId;
		this.forumTitle = forumTitle;
		this.forumDesc = forumDesc;
		this.forumPosts = forumPosts;
		this.forumTopics = forumTopics;
		this.forumLast = forumLast;
		this.forumType = forumType;
	}

	@Id
	@GeneratedValue(strategy = IDENTITY)
	@Column(name = "forum_id", unique = true, nullable = false)
	public Integer getForumId() {
		return this.forumId;
	}

	public void setForumId(Integer forumId) {
		this.forumId = forumId;
	}

	@Column(name = "forum_uri", nullable = false)
	public String getForumUri() {
		return this.forumUri;
	}

	public void setForumUri(String forumUri) {
		this.forumUri = forumUri;
	}

	@Column(name = "cat_id", nullable = false)
	public int getCatId() {
		return this.catId;
	}

	public void setCatId(int catId) {
		this.catId = catId;
	}

	@Column(name = "forum_title")
	public String getForumTitle() {
		return this.forumTitle;
	}

	public void setForumTitle(String forumTitle) {
		this.forumTitle = forumTitle;
	}

	@Column(name = "forum_desc", nullable = false)
	public String getForumDesc() {
		return this.forumDesc;
	}

	public void setForumDesc(String forumDesc) {
		this.forumDesc = forumDesc;
	}

	@Column(name = "forum_posts", nullable = false)
	public int getForumPosts() {
		return this.forumPosts;
	}

	public void setForumPosts(int forumPosts) {
		this.forumPosts = forumPosts;
	}

	@Column(name = "forum_topics", nullable = false)
	public int getForumTopics() {
		return this.forumTopics;
	}

	public void setForumTopics(int forumTopics) {
		this.forumTopics = forumTopics;
	}

	@Column(name = "forum_last", nullable = false)
	public int getForumLast() {
		return this.forumLast;
	}

	public void setForumLast(int forumLast) {
		this.forumLast = forumLast;
	}

	@Column(name = "forum_type", nullable = false, length = 7)
	public String getForumType() {
		return this.forumType;
	}

	public void setForumType(String forumType) {
		this.forumType = forumType;
	}

}
