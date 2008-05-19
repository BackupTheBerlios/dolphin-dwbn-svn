package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import static javax.persistence.GenerationType.IDENTITY;
import javax.persistence.Id;
import javax.persistence.Table;

/**
 * GlParamsKateg generated by hbm2java
 */
@Entity
@Table(name = "GlParamsKateg", catalog = "dolphin")
public class GlParamsKateg implements java.io.Serializable {

	private Integer id;
	private String name;
	private Float menuOrder;

	public GlParamsKateg() {
	}

	public GlParamsKateg(String name) {
		this.name = name;
	}

	public GlParamsKateg(String name, Float menuOrder) {
		this.name = name;
		this.menuOrder = menuOrder;
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

	@Column(name = "name", nullable = false, length = 50)
	public String getName() {
		return this.name;
	}

	public void setName(String name) {
		this.name = name;
	}

	@Column(name = "menu_order", precision = 12, scale = 0)
	public Float getMenuOrder() {
		return this.menuOrder;
	}

	public void setMenuOrder(Float menuOrder) {
		this.menuOrder = menuOrder;
	}

}
