package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import static javax.persistence.GenerationType.IDENTITY;
import javax.persistence.Id;
import javax.persistence.Table;
import javax.persistence.UniqueConstraint;

/**
 * RayChatRooms generated by hbm2java
 */
@Entity
@Table(name = "RayChatRooms", catalog = "dolphin", uniqueConstraints = @UniqueConstraint(columnNames = "Name"))
public class RayChatRooms implements java.io.Serializable {

	private Integer id;
	private String name;
	private String password;
	private String desc;
	private String ownerId;
	private Integer when;
	private String status;

	public RayChatRooms() {
	}

	public RayChatRooms(String name, String password, String desc,
			String ownerId, String status) {
		this.name = name;
		this.password = password;
		this.desc = desc;
		this.ownerId = ownerId;
		this.status = status;
	}

	public RayChatRooms(String name, String password, String desc,
			String ownerId, Integer when, String status) {
		this.name = name;
		this.password = password;
		this.desc = desc;
		this.ownerId = ownerId;
		this.when = when;
		this.status = status;
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

	@Column(name = "Name", unique = true, nullable = false)
	public String getName() {
		return this.name;
	}

	public void setName(String name) {
		this.name = name;
	}

	@Column(name = "Password", nullable = false)
	public String getPassword() {
		return this.password;
	}

	public void setPassword(String password) {
		this.password = password;
	}

	@Column(name = "Desc", nullable = false, length = 65535)
	public String getDesc() {
		return this.desc;
	}

	public void setDesc(String desc) {
		this.desc = desc;
	}

	@Column(name = "OwnerID", nullable = false, length = 20)
	public String getOwnerId() {
		return this.ownerId;
	}

	public void setOwnerId(String ownerId) {
		this.ownerId = ownerId;
	}

	@Column(name = "When")
	public Integer getWhen() {
		return this.when;
	}

	public void setWhen(Integer when) {
		this.when = when;
	}

	@Column(name = "Status", nullable = false, length = 7)
	public String getStatus() {
		return this.status;
	}

	public void setStatus(String status) {
		this.status = status;
	}

}
