package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Id;
import javax.persistence.Table;

/**
 * RayChatCurrentUsers generated by hbm2java
 */
@Entity
@Table(name = "RayChatCurrentUsers", catalog = "dolphin")
public class RayChatCurrentUsers implements java.io.Serializable {

	private String id;
	private String nick;
	private String sex;
	private int age;
	private String desc;
	private String photo;
	private String profile;
	private String online;
	private int start;
	private int when;
	private String status;

	public RayChatCurrentUsers() {
	}

	public RayChatCurrentUsers(String id, String nick, String sex, int age,
			String desc, String photo, String profile, String online,
			int start, int when, String status) {
		this.id = id;
		this.nick = nick;
		this.sex = sex;
		this.age = age;
		this.desc = desc;
		this.photo = photo;
		this.profile = profile;
		this.online = online;
		this.start = start;
		this.when = when;
		this.status = status;
	}

	@Id
	@Column(name = "ID", unique = true, nullable = false, length = 20)
	public String getId() {
		return this.id;
	}

	public void setId(String id) {
		this.id = id;
	}

	@Column(name = "Nick", nullable = false, length = 36)
	public String getNick() {
		return this.nick;
	}

	public void setNick(String nick) {
		this.nick = nick;
	}

	@Column(name = "Sex", nullable = false, length = 2)
	public String getSex() {
		return this.sex;
	}

	public void setSex(String sex) {
		this.sex = sex;
	}

	@Column(name = "Age", nullable = false)
	public int getAge() {
		return this.age;
	}

	public void setAge(int age) {
		this.age = age;
	}

	@Column(name = "Desc", nullable = false, length = 65535)
	public String getDesc() {
		return this.desc;
	}

	public void setDesc(String desc) {
		this.desc = desc;
	}

	@Column(name = "Photo", nullable = false)
	public String getPhoto() {
		return this.photo;
	}

	public void setPhoto(String photo) {
		this.photo = photo;
	}

	@Column(name = "Profile", nullable = false)
	public String getProfile() {
		return this.profile;
	}

	public void setProfile(String profile) {
		this.profile = profile;
	}

	@Column(name = "Online", nullable = false, length = 7)
	public String getOnline() {
		return this.online;
	}

	public void setOnline(String online) {
		this.online = online;
	}

	@Column(name = "Start", nullable = false)
	public int getStart() {
		return this.start;
	}

	public void setStart(int start) {
		this.start = start;
	}

	@Column(name = "When", nullable = false)
	public int getWhen() {
		return this.when;
	}

	public void setWhen(int when) {
		this.when = when;
	}

	@Column(name = "Status", nullable = false, length = 6)
	public String getStatus() {
		return this.status;
	}

	public void setStatus(String status) {
		this.status = status;
	}

}
