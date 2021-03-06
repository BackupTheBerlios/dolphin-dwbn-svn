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
 * Aff generated by hbm2java
 */
@Entity
@Table(name = "aff", catalog = "dolphin")
public class Aff implements java.io.Serializable {

	private Long id;
	private String name;
	private String email;
	private String password;
	private double percent;
	private int seed;
	private Date regDate;
	private String status;
	private String www1;
	private String www2;

	public Aff() {
	}

	public Aff(String name, String email, String password, double percent,
			int seed, Date regDate, String status, String www1, String www2) {
		this.name = name;
		this.email = email;
		this.password = password;
		this.percent = percent;
		this.seed = seed;
		this.regDate = regDate;
		this.status = status;
		this.www1 = www1;
		this.www2 = www2;
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

	@Column(name = "Name", nullable = false, length = 10)
	public String getName() {
		return this.name;
	}

	public void setName(String name) {
		this.name = name;
	}

	@Column(name = "email", nullable = false)
	public String getEmail() {
		return this.email;
	}

	public void setEmail(String email) {
		this.email = email;
	}

	@Column(name = "Password", nullable = false, length = 32)
	public String getPassword() {
		return this.password;
	}

	public void setPassword(String password) {
		this.password = password;
	}

	@Column(name = "Percent", nullable = false, precision = 22, scale = 0)
	public double getPercent() {
		return this.percent;
	}

	public void setPercent(double percent) {
		this.percent = percent;
	}

	@Column(name = "seed", nullable = false)
	public int getSeed() {
		return this.seed;
	}

	public void setSeed(int seed) {
		this.seed = seed;
	}

	@Temporal(TemporalType.TIMESTAMP)
	@Column(name = "RegDate", nullable = false, length = 0)
	public Date getRegDate() {
		return this.regDate;
	}

	public void setRegDate(Date regDate) {
		this.regDate = regDate;
	}

	@Column(name = "Status", nullable = false, length = 9)
	public String getStatus() {
		return this.status;
	}

	public void setStatus(String status) {
		this.status = status;
	}

	@Column(name = "www1", nullable = false, length = 10)
	public String getWww1() {
		return this.www1;
	}

	public void setWww1(String www1) {
		this.www1 = www1;
	}

	@Column(name = "www2", nullable = false, length = 10)
	public String getWww2() {
		return this.www2;
	}

	public void setWww2(String www2) {
		this.www2 = www2;
	}

}
