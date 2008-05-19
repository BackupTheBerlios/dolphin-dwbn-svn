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

/**
 * GalleryAlbums generated by hbm2java
 */
@Entity
@Table(name = "GalleryAlbums", catalog = "dolphin")
public class GalleryAlbums implements java.io.Serializable {

	private Integer id;
	private long idmember;
	private String name;
	private String comment;
	private Date created;
	private Date modified;
	private String access;

	public GalleryAlbums() {
	}

	public GalleryAlbums(long idmember, String name, Date created,
			Date modified, String access) {
		this.idmember = idmember;
		this.name = name;
		this.created = created;
		this.modified = modified;
		this.access = access;
	}

	public GalleryAlbums(long idmember, String name, String comment,
			Date created, Date modified, String access) {
		this.idmember = idmember;
		this.name = name;
		this.comment = comment;
		this.created = created;
		this.modified = modified;
		this.access = access;
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

	@Column(name = "IDMember", nullable = false)
	public long getIdmember() {
		return this.idmember;
	}

	public void setIdmember(long idmember) {
		this.idmember = idmember;
	}

	@Column(name = "Name", nullable = false)
	public String getName() {
		return this.name;
	}

	public void setName(String name) {
		this.name = name;
	}

	@Column(name = "Comment")
	public String getComment() {
		return this.comment;
	}

	public void setComment(String comment) {
		this.comment = comment;
	}

	@Temporal(TemporalType.TIMESTAMP)
	@Column(name = "Created", nullable = false, length = 0)
	public Date getCreated() {
		return this.created;
	}

	public void setCreated(Date created) {
		this.created = created;
	}

	@Temporal(TemporalType.TIMESTAMP)
	@Column(name = "Modified", nullable = false, length = 0)
	public Date getModified() {
		return this.modified;
	}

	public void setModified(Date modified) {
		this.modified = modified;
	}

	@Column(name = "Access", nullable = false, length = 7)
	public String getAccess() {
		return this.access;
	}

	public void setAccess(String access) {
		this.access = access;
	}

}
