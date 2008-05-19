package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.AttributeOverride;
import javax.persistence.AttributeOverrides;
import javax.persistence.Column;
import javax.persistence.EmbeddedId;
import javax.persistence.Entity;
import javax.persistence.Table;

/**
 * BannersShows generated by hbm2java
 */
@Entity
@Table(name = "BannersShows", catalog = "dolphin")
public class BannersShows implements java.io.Serializable {

	private BannersShowsId id;

	public BannersShows() {
	}

	public BannersShows(BannersShowsId id) {
		this.id = id;
	}

	@EmbeddedId
	@AttributeOverrides( {
			@AttributeOverride(name = "id", column = @Column(name = "ID", nullable = false)),
			@AttributeOverride(name = "date", column = @Column(name = "Date", nullable = false, length = 0)),
			@AttributeOverride(name = "ip", column = @Column(name = "IP", nullable = false, length = 16)) })
	public BannersShowsId getId() {
		return this.id;
	}

	public void setId(BannersShowsId id) {
		this.id = id;
	}

}
