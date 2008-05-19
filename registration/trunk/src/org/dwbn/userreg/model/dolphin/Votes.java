package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.AttributeOverride;
import javax.persistence.AttributeOverrides;
import javax.persistence.Column;
import javax.persistence.EmbeddedId;
import javax.persistence.Entity;
import javax.persistence.Table;
import javax.persistence.UniqueConstraint;

/**
 * Votes generated by hbm2java
 */
@Entity
@Table(name = "Votes", catalog = "dolphin", uniqueConstraints = @UniqueConstraint(columnNames = {
		"Member", "IP", "Date" }))
public class Votes implements java.io.Serializable {

	private VotesId id;

	public Votes() {
	}

	public Votes(VotesId id) {
		this.id = id;
	}

	@EmbeddedId
	@AttributeOverrides( {
			@AttributeOverride(name = "member", column = @Column(name = "Member", nullable = false)),
			@AttributeOverride(name = "mark", column = @Column(name = "Mark", nullable = false)),
			@AttributeOverride(name = "ip", column = @Column(name = "IP", nullable = false, length = 18)),
			@AttributeOverride(name = "date", column = @Column(name = "Date", nullable = false, length = 0)) })
	public VotesId getId() {
		return this.id;
	}

	public void setId(VotesId id) {
		this.id = id;
	}

}
