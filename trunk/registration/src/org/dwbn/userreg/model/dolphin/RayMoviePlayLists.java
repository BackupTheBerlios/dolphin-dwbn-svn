package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.AttributeOverride;
import javax.persistence.AttributeOverrides;
import javax.persistence.Column;
import javax.persistence.EmbeddedId;
import javax.persistence.Entity;
import javax.persistence.Table;

/**
 * RayMoviePlayLists generated by hbm2java
 */
@Entity
@Table(name = "RayMoviePlayLists", catalog = "dolphin")
public class RayMoviePlayLists implements java.io.Serializable {

	private RayMoviePlayListsId id;

	public RayMoviePlayLists() {
	}

	public RayMoviePlayLists(RayMoviePlayListsId id) {
		this.id = id;
	}

	@EmbeddedId
	@AttributeOverrides( {
			@AttributeOverride(name = "fileId", column = @Column(name = "FileId", nullable = false)),
			@AttributeOverride(name = "owner", column = @Column(name = "Owner", nullable = false, length = 64)),
			@AttributeOverride(name = "order", column = @Column(name = "Order", nullable = false)) })
	public RayMoviePlayListsId getId() {
		return this.id;
	}

	public void setId(RayMoviePlayListsId id) {
		this.id = id;
	}

}