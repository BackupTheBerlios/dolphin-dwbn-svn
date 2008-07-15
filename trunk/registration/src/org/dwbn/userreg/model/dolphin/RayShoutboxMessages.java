package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import static javax.persistence.GenerationType.IDENTITY;
import javax.persistence.Id;
import javax.persistence.Table;

/**
 * RayShoutboxMessages generated by hbm2java
 */
@Entity
@Table(name = "RayShoutboxMessages", catalog = "dolphin")
public class RayShoutboxMessages implements java.io.Serializable {

	private Integer id;
	private String userId;
	private String msg;
	private int when;

	public RayShoutboxMessages() {
	}

	public RayShoutboxMessages(String userId, String msg, int when) {
		this.userId = userId;
		this.msg = msg;
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

	@Column(name = "UserID", nullable = false, length = 20)
	public String getUserId() {
		return this.userId;
	}

	public void setUserId(String userId) {
		this.userId = userId;
	}

	@Column(name = "Msg", nullable = false, length = 65535)
	public String getMsg() {
		return this.msg;
	}

	public void setMsg(String msg) {
		this.msg = msg;
	}

	@Column(name = "When", nullable = false)
	public int getWhen() {
		return this.when;
	}

	public void setWhen(int when) {
		this.when = when;
	}

}