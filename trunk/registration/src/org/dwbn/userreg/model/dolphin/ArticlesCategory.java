package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import static javax.persistence.GenerationType.IDENTITY;
import javax.persistence.Id;
import javax.persistence.Table;
import javax.persistence.UniqueConstraint;

/**
 * ArticlesCategory generated by hbm2java
 */
@Entity
@Table(name = "ArticlesCategory", catalog = "dolphin", uniqueConstraints = @UniqueConstraint(columnNames = "CategoryUri"))
public class ArticlesCategory implements java.io.Serializable {

	private Integer categoryId;
	private String categoryName;
	private String categoryUri;
	private String categoryDescription;

	public ArticlesCategory() {
	}

	public ArticlesCategory(String categoryName, String categoryUri) {
		this.categoryName = categoryName;
		this.categoryUri = categoryUri;
	}

	public ArticlesCategory(String categoryName, String categoryUri,
			String categoryDescription) {
		this.categoryName = categoryName;
		this.categoryUri = categoryUri;
		this.categoryDescription = categoryDescription;
	}

	@Id
	@GeneratedValue(strategy = IDENTITY)
	@Column(name = "CategoryID", unique = true, nullable = false)
	public Integer getCategoryId() {
		return this.categoryId;
	}

	public void setCategoryId(Integer categoryId) {
		this.categoryId = categoryId;
	}

	@Column(name = "CategoryName", nullable = false)
	public String getCategoryName() {
		return this.categoryName;
	}

	public void setCategoryName(String categoryName) {
		this.categoryName = categoryName;
	}

	@Column(name = "CategoryUri", unique = true, nullable = false)
	public String getCategoryUri() {
		return this.categoryUri;
	}

	public void setCategoryUri(String categoryUri) {
		this.categoryUri = categoryUri;
	}

	@Column(name = "CategoryDescription")
	public String getCategoryDescription() {
		return this.categoryDescription;
	}

	public void setCategoryDescription(String categoryDescription) {
		this.categoryDescription = categoryDescription;
	}

}