package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import java.util.Date;
import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import static javax.persistence.GenerationType.IDENTITY;
import javax.persistence.Id;
import javax.persistence.Table;
import javax.persistence.Temporal;
import javax.persistence.TemporalType;
import javax.persistence.UniqueConstraint;

/**
 * Articles generated by hbm2java
 */
@Entity
@Table(name = "Articles", catalog = "dolphin", uniqueConstraints = @UniqueConstraint(columnNames = "ArticleUri"))
public class Articles implements java.io.Serializable {

	private Long articlesId;
	private Integer categoryId;
	private Date date;
	private String title;
	private String articleUri;
	private String text;
	private String articleFlag;
	private int ownerId;

	public Articles() {
	}

	public Articles(Date date, String title, String articleUri,
			String articleFlag, int ownerId) {
		this.date = date;
		this.title = title;
		this.articleUri = articleUri;
		this.articleFlag = articleFlag;
		this.ownerId = ownerId;
	}

	public Articles(Integer categoryId, Date date, String title,
			String articleUri, String text, String articleFlag, int ownerId) {
		this.categoryId = categoryId;
		this.date = date;
		this.title = title;
		this.articleUri = articleUri;
		this.text = text;
		this.articleFlag = articleFlag;
		this.ownerId = ownerId;
	}

	@Id
	@GeneratedValue(strategy = IDENTITY)
	@Column(name = "ArticlesID", unique = true, nullable = false)
	public Long getArticlesId() {
		return this.articlesId;
	}

	public void setArticlesId(Long articlesId) {
		this.articlesId = articlesId;
	}

	@Column(name = "CategoryID")
	public Integer getCategoryId() {
		return this.categoryId;
	}

	public void setCategoryId(Integer categoryId) {
		this.categoryId = categoryId;
	}

	@Temporal(TemporalType.DATE)
	@Column(name = "Date", nullable = false, length = 0)
	public Date getDate() {
		return this.date;
	}

	public void setDate(Date date) {
		this.date = date;
	}

	@Column(name = "Title", nullable = false, length = 100)
	public String getTitle() {
		return this.title;
	}

	public void setTitle(String title) {
		this.title = title;
	}

	@Column(name = "ArticleUri", unique = true, nullable = false, length = 100)
	public String getArticleUri() {
		return this.articleUri;
	}

	public void setArticleUri(String articleUri) {
		this.articleUri = articleUri;
	}

	@Column(name = "Text", length = 16777215)
	public String getText() {
		return this.text;
	}

	public void setText(String text) {
		this.text = text;
	}

	@Column(name = "ArticleFlag", nullable = false, length = 5)
	public String getArticleFlag() {
		return this.articleFlag;
	}

	public void setArticleFlag(String articleFlag) {
		this.articleFlag = articleFlag;
	}

	@Column(name = "ownerID", nullable = false)
	public int getOwnerId() {
		return this.ownerId;
	}

	public void setOwnerId(int ownerId) {
		this.ownerId = ownerId;
	}

}
