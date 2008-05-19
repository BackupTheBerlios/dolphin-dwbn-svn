package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import static javax.persistence.GenerationType.IDENTITY;
import javax.persistence.Id;
import javax.persistence.Table;

/**
 * PollsA generated by hbm2java
 */
@Entity
@Table(name = "polls_a", catalog = "dolphin")
public class PollsA implements java.io.Serializable {

	private Integer idanswer;
	private int id;
	private String answer;
	private int votes;

	public PollsA() {
	}

	public PollsA(int id, String answer, int votes) {
		this.id = id;
		this.answer = answer;
		this.votes = votes;
	}

	@Id
	@GeneratedValue(strategy = IDENTITY)
	@Column(name = "IDanswer", unique = true, nullable = false)
	public Integer getIdanswer() {
		return this.idanswer;
	}

	public void setIdanswer(Integer idanswer) {
		this.idanswer = idanswer;
	}

	@Column(name = "ID", nullable = false)
	public int getId() {
		return this.id;
	}

	public void setId(int id) {
		this.id = id;
	}

	@Column(name = "Answer", nullable = false)
	public String getAnswer() {
		return this.answer;
	}

	public void setAnswer(String answer) {
		this.answer = answer;
	}

	@Column(name = "Votes", nullable = false)
	public int getVotes() {
		return this.votes;
	}

	public void setVotes(int votes) {
		this.votes = votes;
	}

}
