package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import static javax.persistence.GenerationType.IDENTITY;
import javax.persistence.Id;
import javax.persistence.Table;

/**
 * DailyQuotes generated by hbm2java
 */
@Entity
@Table(name = "DailyQuotes", catalog = "dolphin")
public class DailyQuotes implements java.io.Serializable {

	private Integer id;
	private String text;
	private String author;

	public DailyQuotes() {
	}

	public DailyQuotes(String text, String author) {
		this.text = text;
		this.author = author;
	}

	@Id
	@GeneratedValue(strategy = IDENTITY)
	@Column(name = "ID", unique = true, nullable = false)
	public Integer getId() {
		return this.id;
	}

	public void setId(Integer id) {
		this.id = id;
	}

	@Column(name = "Text", nullable = false, length = 16777215)
	public String getText() {
		return this.text;
	}

	public void setText(String text) {
		this.text = text;
	}

	@Column(name = "Author", nullable = false, length = 128)
	public String getAuthor() {
		return this.author;
	}

	public void setAuthor(String author) {
		this.author = author;
	}

}
