package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.AttributeOverride;
import javax.persistence.AttributeOverrides;
import javax.persistence.Column;
import javax.persistence.EmbeddedId;
import javax.persistence.Entity;
import javax.persistence.Table;

/**
 * AdminLinks generated by hbm2java
 */
@Entity
@Table(name = "AdminLinks", catalog = "dolphin")
public class AdminLinks implements java.io.Serializable {

	private AdminLinksId id;

	public AdminLinks() {
	}

	public AdminLinks(AdminLinksId id) {
		this.id = id;
	}

	@EmbeddedId
	@AttributeOverrides( {
			@AttributeOverride(name = "title", column = @Column(name = "Title", nullable = false, length = 30)),
			@AttributeOverride(name = "url", column = @Column(name = "Url", nullable = false, length = 150)) })
	public AdminLinksId getId() {
		return this.id;
	}

	public void setId(AdminLinksId id) {
		this.id = id;
	}

}
