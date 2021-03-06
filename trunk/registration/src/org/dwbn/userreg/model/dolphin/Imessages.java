package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.AttributeOverride;
import javax.persistence.AttributeOverrides;
import javax.persistence.Column;
import javax.persistence.EmbeddedId;
import javax.persistence.Entity;
import javax.persistence.Table;

/**
 * Imessages generated by hbm2java
 */
@Entity
@Table(name = "IMessages", catalog = "dolphin")
public class Imessages implements java.io.Serializable {

	private ImessagesId id;

	public Imessages() {
	}

	public Imessages(ImessagesId id) {
		this.id = id;
	}

	@EmbeddedId
	@AttributeOverrides( {
			@AttributeOverride(name = "idfrom", column = @Column(name = "IDFrom", nullable = false)),
			@AttributeOverride(name = "idto", column = @Column(name = "IDTo", nullable = false)),
			@AttributeOverride(name = "when", column = @Column(name = "When", nullable = false, length = 0)),
			@AttributeOverride(name = "msg", column = @Column(name = "Msg", nullable = false)) })
	public ImessagesId getId() {
		return this.id;
	}

	public void setId(ImessagesId id) {
		this.id = id;
	}

}
