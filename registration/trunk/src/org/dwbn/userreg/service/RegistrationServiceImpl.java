package org.dwbn.userreg.service;

import java.sql.Date;
import java.util.List;

import javax.persistence.EntityManager;
import javax.persistence.PersistenceContext;
import javax.persistence.Query;

import org.dwbn.userreg.model.Registration;
import org.springframework.transaction.annotation.Transactional;

@Transactional
public class RegistrationServiceImpl implements RegistrationService {
	private EntityManager em;

	@PersistenceContext
	public void setEntityManager(EntityManager em) {
		this.em = em;
	}

	private EntityManager getEntityManager() {
		return em;
	}

	@SuppressWarnings("unchecked")
	public List<Registration> findAll() {
		Query query = getEntityManager().createQuery(
				"SELECT r FROM Registration r");
		return query.getResultList();
	}

	public void save(Registration reg) {
		if (reg.getId() == null) {
			// Create a new DB entry
			em.persist(reg);
		} else {
			// Update DB entry
			em.merge(reg);
		}
	}

	public void remove(int id) {
		Registration rw = find(id);
		if (rw != null) {
			em.remove(rw);
		}
	}

	public Registration find(int id) {
		return em.find(Registration.class, id);
	}

	// After clicking on the confirmattion link, change registration statuts
	// from waiting to cofirmed
	public void changeStatusWaitingToConfirmed(int id) {
		/* @TODO: change statuts from waiting to cofirmed */
	}

	@SuppressWarnings("unchecked")
	public void cleanUp( /* @TODO int days */) {
		Query query = getEntityManager().createNativeQuery(
				"DELETE FROM Registration WHERE regdate = '"
						+ new Date((new java.util.Date()).getTime()) + "'");
		query.executeUpdate();
	}

	@Override
	public int getCount() {
		Number number = (Number) getEntityManager().createQuery(
				"SELECT COUNT(r) FROM Registration r").getSingleResult();
		return number.intValue();
	}

	@Override
	public List<Registration> getRegistrationListByRange(Integer first,
			int numberOfRows) {
		Query query = getEntityManager().createQuery(
				"SELECT r FROM Registration r WHERE id < " + first
						+ " AND id > " + (first + numberOfRows));
		return query.getResultList();
	}
}