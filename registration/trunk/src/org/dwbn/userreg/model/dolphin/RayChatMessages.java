package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import static javax.persistence.GenerationType.IDENTITY;
import javax.persistence.Id;
import javax.persistence.Table;

/**
 * RayChatMessages generated by hbm2java
 */
@Entity
@Table(name = "RayChatMessages", catalog = "dolphin")
public class RayChatMessages implements java.io.Serializable {

	private Integer id;
	private int room;
	private String sender;
	private String recipient;
	private String whisper;
	private String message;
	private String style;
	private String type;
	private int when;

	public RayChatMessages() {
	}

	public RayChatMessages(int room, String sender, String recipient,
			String whisper, String message, String style, String type, int when) {
		this.room = room;
		this.sender = sender;
		this.recipient = recipient;
		this.whisper = whisper;
		this.message = message;
		this.style = style;
		this.type = type;
		this.when = when;
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

	@Column(name = "Room", nullable = false)
	public int getRoom() {
		return this.room;
	}

	public void setRoom(int room) {
		this.room = room;
	}

	@Column(name = "Sender", nullable = false, length = 20)
	public String getSender() {
		return this.sender;
	}

	public void setSender(String sender) {
		this.sender = sender;
	}

	@Column(name = "Recipient", nullable = false, length = 20)
	public String getRecipient() {
		return this.recipient;
	}

	public void setRecipient(String recipient) {
		this.recipient = recipient;
	}

	@Column(name = "Whisper", nullable = false, length = 5)
	public String getWhisper() {
		return this.whisper;
	}

	public void setWhisper(String whisper) {
		this.whisper = whisper;
	}

	@Column(name = "Message", nullable = false, length = 65535)
	public String getMessage() {
		return this.message;
	}

	public void setMessage(String message) {
		this.message = message;
	}

	@Column(name = "Style", nullable = false, length = 65535)
	public String getStyle() {
		return this.style;
	}

	public void setStyle(String style) {
		this.style = style;
	}

	@Column(name = "Type", nullable = false, length = 5)
	public String getType() {
		return this.type;
	}

	public void setType(String type) {
		this.type = type;
	}

	@Column(name = "When", nullable = false)
	public int getWhen() {
		return this.when;
	}

	public void setWhen(int when) {
		this.when = when;
	}

}
