package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import static javax.persistence.GenerationType.IDENTITY;
import javax.persistence.Id;
import javax.persistence.Table;

/**
 * AdminMenu generated by hbm2java
 */
@Entity
@Table(name = "AdminMenu", catalog = "dolphin")
public class AdminMenu implements java.io.Serializable {

	private Integer id;
	private String title;
	private String url;
	private String desc;
	private String check;
	private float order;
	private int categ;
	private String icon;

	public AdminMenu() {
	}

	public AdminMenu(String title, String url, String desc, String check,
			float order, int categ, String icon) {
		this.title = title;
		this.url = url;
		this.desc = desc;
		this.check = check;
		this.order = order;
		this.categ = categ;
		this.icon = icon;
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

	@Column(name = "Title", nullable = false, length = 50)
	public String getTitle() {
		return this.title;
	}

	public void setTitle(String title) {
		this.title = title;
	}

	@Column(name = "Url", nullable = false)
	public String getUrl() {
		return this.url;
	}

	public void setUrl(String url) {
		this.url = url;
	}

	@Column(name = "Desc", nullable = false)
	public String getDesc() {
		return this.desc;
	}

	public void setDesc(String desc) {
		this.desc = desc;
	}

	@Column(name = "Check", nullable = false)
	public String getCheck() {
		return this.check;
	}

	public void setCheck(String check) {
		this.check = check;
	}

	@Column(name = "Order", nullable = false, precision = 12, scale = 0)
	public float getOrder() {
		return this.order;
	}

	public void setOrder(float order) {
		this.order = order;
	}

	@Column(name = "Categ", nullable = false)
	public int getCateg() {
		return this.categ;
	}

	public void setCateg(int categ) {
		this.categ = categ;
	}

	@Column(name = "Icon", nullable = false, length = 100)
	public String getIcon() {
		return this.icon;
	}

	public void setIcon(String icon) {
		this.icon = icon;
	}

}
