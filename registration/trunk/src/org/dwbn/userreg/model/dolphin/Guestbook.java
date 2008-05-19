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
 * Guestbook generated by hbm2java
 */
@Entity
@Table(name = "Guestbook", catalog = "dolphin")
public class Guestbook implements java.io.Serializable {

	private Long id;
	private Date date;
	private String ip;
	private long sender;
	private long recipient;
	private String text;
	private String new_;

	public Guestbook() {
	}

	public Guestbook(Date date, long sender, long recipient, String text,
			String new_) {
		this.date = date;
		this.sender = sender;
		this.recipient = recipient;
		this.text = text;
		this.new_ = new_;
	}

	public Guestbook(Date date, String ip, long sender, long recipient,
			String text, String new_) {
		this.date = date;
		this.ip = ip;
		this.sender = sender;
		this.recipient = recipient;
		this.text = text;
		this.new_ = new_;
	}

	@Id
	@GeneratedValue(strategy = IDENTITY)
	@Column(name = "ID", unique = true, nullable = false)
	public Long getId() {
		return this.id;
	}

	public void setId(Long id) {
		this.id = id;
	}

	@Temporal(TemporalType.TIMESTAMP)
	@Column(name = "Date", nullable = false, length = 0)
	public Date getDate() {
		return this.date;
	}

	public void setDate(Date date) {
		this.date = date;
	}

	@Column(name = "IP", length = 16)
	public String getIp() {
		return this.ip;
	}

	public void setIp(String ip) {
		this.ip = ip;
	}

	@Column(name = "Sender", nullable = false)
	public long getSender() {
		return this.sender;
	}

	public void setSender(long sender) {
		this.sender = sender;
	}

	@Column(name = "Recipient", nullable = false)
	public long getRecipient() {
		return this.recipient;
	}

	public void setRecipient(long recipient) {
		this.recipient = recipient;
	}

	@Column(name = "Text", nullable = false, length = 16777215)
	public String getText() {
		return this.text;
	}

	public void setText(String text) {
		this.text = text;
	}

	@Column(name = "New", nullable = false, length = 2)
	public String getNew_() {
		return this.new_;
	}

	public void setNew_(String new_) {
		this.new_ = new_;
	}

}
